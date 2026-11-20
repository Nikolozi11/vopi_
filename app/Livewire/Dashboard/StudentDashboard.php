<?php

namespace App\Livewire\Dashboard;

use App\Models\StudentProfile;
use App\Models\Task;
use App\Services\AiPlanner;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentDashboard extends Component
{
    use WithFileUploads;

    // დავალების ფორმა
    public string $title = '';
    public string $description = '';
    public string $type = 'school_task';
    public string $start_time = '';
    public string $end_time = '';

    // რედაქტირება
    public ?int $editingId = null;
    public bool $ignoreConflicts = false;
    public array $conflictWarnings = [];

    // AI ჩატი
    public bool $isChatOpen = false;
    public string $aiMessage = '';
    public $photo = null;
    public bool $pushEnabled = false;
    public int $daysShown = 7;

    /** @var array<int, array{sender: string, text: string}> */
    public array $chatHistory = [];

    public function mount(): void
    {
        $this->chatHistory[] = [
            'sender' => 'ai',
            'text' => 'გამარჯობა! მე ვარ შენი სასწავლო ასისტენტი. მომწერე ან ხმით მითხარი დავალება (მაგ: "მათემატიკა გვ. 45 №3"), ან ატვირთე შენი გრაფიკის ფოტო — დანარჩენს მე მივხედავ!',
        ];

        $this->pushEnabled = DB::table('push_subscriptions')->where('user_id', Auth::id())->exists();
    }

    public function toggleChat(): void
    {
        $this->isChatOpen = ! $this->isChatOpen;
    }

    // ------------------------------------------------------------ ხელით დავალებები

    protected function taskRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:school_task,tutor,activity,break',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ];
    }

    public function saveTask(ScheduleService $schedule): void
    {
        $this->validate($this->taskRules());

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        if (! $this->ignoreConflicts) {
            // ხელით ჩანიშვნისას ვაფრთხილებთ, მაგრამ არ ვზღუდავთ — მომხმარებელი ადასტურებს
            $this->conflictWarnings = $schedule->conflicts(Auth::user(), $start, $end, $this->type, $this->editingId, false);
            if ($this->conflictWarnings) {
                return; // მომხმარებელმა უნდა დაადასტუროს
            }
        }

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'type' => $this->type,
            'start_time' => $start,
            'end_time' => $end,
        ];

        if ($this->editingId) {
            $data['notified_at'] = null; // დროის შეცვლისას შეხსენება თავიდან გაიგზავნოს
            Task::where('user_id', Auth::id())->findOrFail($this->editingId)->update($data);
        } else {
            Task::create($data + ['user_id' => Auth::id(), 'is_completed' => false]);
        }

        $this->resetForm();
    }

    public function confirmConflict(ScheduleService $schedule): void
    {
        $this->ignoreConflicts = true;
        $this->saveTask($schedule);
    }

    public function editTask(int $taskId): void
    {
        $t = Task::where('user_id', Auth::id())->findOrFail($taskId);
        $this->editingId = $t->id;
        $this->title = $t->title;
        $this->description = (string) $t->description;
        $this->type = $t->type;
        $this->start_time = $t->start_time->format('Y-m-d\TH:i');
        $this->end_time = $t->end_time->format('Y-m-d\TH:i');
        $this->conflictWarnings = [];
        $this->ignoreConflicts = false;
    }

    public function cancelEdit(): void
    {
        $this->resetForm();
    }

    public function deleteTask(int $taskId): void
    {
        Task::where('user_id', Auth::id())->where('id', $taskId)->delete();
        if ($this->editingId === $taskId) {
            $this->resetForm();
        }
    }

    public function deleteCompleted(): void
    {
        Task::where('user_id', Auth::id())->where('is_completed', true)->delete();
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'description', 'start_time', 'end_time', 'editingId', 'ignoreConflicts', 'conflictWarnings']);
        $this->type = 'school_task';
    }

    public function toggleTaskCompletion(int $taskId): void
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);
        $task->update(['is_completed' => ! $task->is_completed]);
    }

    // ------------------------------------------------------------ Push

    public function savePushSubscription(array $subscription): void
    {
        if (! isset($subscription['endpoint'])) {
            return;
        }

        DB::table('push_subscriptions')->updateOrInsert(
            ['user_id' => Auth::id(), 'endpoint' => $subscription['endpoint']],
            [
                'public_key' => $subscription['keys']['p256dh'] ?? null,
                'auth_token' => $subscription['keys']['auth'] ?? null,
                'content_encoding' => 'aes128gcm',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $this->pushEnabled = true;
    }

    public function deletePushSubscription(?string $endpoint = null): void
    {
        $q = DB::table('push_subscriptions')->where('user_id', Auth::id());
        if ($endpoint) {
            $q->where('endpoint', $endpoint);
        }
        $q->delete();
        $this->pushEnabled = false;
    }

    // ------------------------------------------------------------ AI

    public function sendMessageToAi(AiPlanner $planner): void
    {
        $text = trim($this->aiMessage);
        $hasPhoto = $this->photo !== null;

        if ($text === '' && ! $hasPhoto) {
            return;
        }

        $this->validate([
            'aiMessage' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        $imageUri = null;
        $thumb = null;
        if ($hasPhoto) {
            $thumb = $this->makeThumbnail($this->photo->getRealPath());
            $imageUri = 'data:' . $this->photo->getMimeType() . ';base64,' . base64_encode(file_get_contents($this->photo->getRealPath()));
            $this->photo->delete();
            $this->photo = null;
        }

        $shown = $text !== '' ? $text : 'გავაგზავნე გრაფიკის ფოტო';
        $this->chatHistory[] = ['sender' => 'user', 'text' => $shown, 'image' => $thumb];
        $this->aiMessage = '';

        if (! config('services.openrouter.key')) {
            $this->chatHistory[] = ['sender' => 'ai', 'text' => 'AI სერვისი ამჟამად მიუწვდომელია (კონფიგურაციის ხარვეზი).'];
            return;
        }

        // ფოტოსთვის ტექსტი სავალდებულოა API-სთვის
        $history = $this->chatHistory;
        if ($hasPhoto) {
            $history[array_key_last($history)]['text'] = $text !== ''
                ? $text
                : 'ამოიკითხე ამ ფოტოზე დაწერილი დავალებები და ჩამიწერე განრიგში.';
        }

        try {
            $reply = $planner->chat(Auth::user(), $history, $imageUri);
        } catch (\Throwable $e) {
            logger()->error('AI exception: ' . $e->getMessage());
            $reply = 'ბოდიში, კავშირის დროს შეფერხებაა. სცადე თავიდან.';
        }

        $this->chatHistory[] = ['sender' => 'ai', 'text' => $reply];
    }

    /** პატარა JPEG მინიატურა ჩატისთვის (ორიგინალი სერვერზე არ რჩება) */
    private function makeThumbnail(string $path, int $max = 240): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }
        $img = @imagecreatefromstring((string) file_get_contents($path));
        if (! $img) {
            return null;
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $scale = min(1, $max / max($w, $h));
        $tw = max(1, (int) round($w * $scale));
        $th = max(1, (int) round($h * $scale));

        $out = imagecreatetruecolor($tw, $th);
        imagecopyresampled($out, $img, 0, 0, 0, 0, $tw, $th, $w, $h);

        ob_start();
        imagejpeg($out, null, 72);
        $data = (string) ob_get_clean();
        imagedestroy($img);
        imagedestroy($out);

        return 'data:image/jpeg;base64,' . base64_encode($data);
    }

    public function showMoreDays(): void
    {
        $this->daysShown += 7;
    }

    public function render(ScheduleService $schedule)
    {
        $user = Auth::user();
        $from = now()->startOfDay();
        $to = now()->addDays($this->daysShown - 1)->endOfDay();

        $tasks = Task::where('user_id', $user->id)
            ->whereBetween('start_time', [$from, $to])
            ->orderBy('start_time')->get();

        $later = Task::where('user_id', $user->id)->where('start_time', '>', $to)->count();

        $past = Task::where('user_id', $user->id)->where('start_time', '<', $from)
            ->orderByDesc('start_time')->limit(30)->get();

        // დღეები: მუდმივი ბლოკები (სკოლა/რეპეტიტორი/წრე) + დავალებები ერთ ხაზზე
        $days = [];
        for ($i = 0; $i < $this->daysShown; $i++) {
            $d = $from->copy()->addDays($i);
            $days[$d->toDateString()] = ['date' => $d, 'items' => []];
        }

        foreach ($schedule->blocks($user, $from, $to) as $b) {
            $key = $b['start']->toDateString();
            if (isset($days[$key])) {
                $days[$key]['items'][] = ['kind' => $b['kind'], 'label' => $b['label'], 'start' => $b['start'], 'end' => $b['end'], 'task' => null];
            }
        }
        foreach ($tasks as $t) {
            $key = $t->start_time->toDateString();
            if (isset($days[$key])) {
                $days[$key]['items'][] = ['kind' => $t->type, 'label' => $t->title, 'start' => $t->start_time, 'end' => $t->end_time, 'task' => $t];
            }
        }
        foreach ($days as &$day) {
            usort($day['items'], fn ($a, $b) => $a['start'] <=> $b['start']);
        }
        unset($day);

        $todayTasks = $tasks->filter(fn ($t) => $t->start_time->isToday());

        return view('livewire.dashboard.student-dashboard', [
            'tasks' => Task::where('user_id', $user->id)->orderBy('start_time')->get(),
            'days' => $days,
            'past' => $past,
            'later' => $later,
            'todayTotal' => $todayTasks->count(),
            'todayDone' => $todayTasks->where('is_completed', true)->count(),
            'profile' => StudentProfile::where('user_id', $user->id)->first(),
            'firstName' => explode(' ', trim($user->name))[0] ?? '',
        ])->layout('layouts.app');
    }
}
