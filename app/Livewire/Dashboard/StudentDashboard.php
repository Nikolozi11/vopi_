<?php

namespace App\Livewire\Dashboard;

use App\Models\StudentProfile;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class StudentDashboard extends Component
{
    // დავალების ფორმა
    public string $title = '';
    public string $description = '';
    public string $type = 'school_task';
    public string $start_time = '';
    public string $end_time = '';

    // AI ჩატი
    public bool $isChatOpen = false;
    public string $aiMessage = '';

    /** @var array<int, array{sender: string, text: string}> */
    public array $chatHistory = [];

    public function mount(): void
    {
        $this->chatHistory[] = [
            'sender' => 'ai',
            'text' => 'გამარჯობა! მე ვარ შენი სასწავლო ასისტენტი. მითხარი შენი გრაფიკი ან დავალებები და სიამოვნებით დაგეხმარები მათ განრიგში განაწილებაში!',
        ];
    }

    public function toggleChat(): void
    {
        $this->isChatOpen = ! $this->isChatOpen;
    }

    public function addTask(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:school_task,tutor,activity,break',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_completed' => false,
        ]);

        $this->reset(['title', 'description', 'start_time', 'end_time']);
        $this->type = 'school_task';
    }

    public function toggleTaskCompletion(int $taskId): void
    {
        $task = Task::where('user_id', Auth::id())->findOrFail($taskId);
        $task->update(['is_completed' => ! $task->is_completed]);
    }

    public function sendMessageToAi(): void
    {
        $text = trim($this->aiMessage);

        if ($text === '') {
            return;
        }

        $this->validate(['aiMessage' => 'string|max:2000']);

        $this->chatHistory[] = ['sender' => 'user', 'text' => $text];
        $this->aiMessage = '';

        $apiKey = config('services.openrouter.key');

        if (! $apiKey) {
            $this->chatHistory[] = [
                'sender' => 'ai',
                'text' => 'AI სერვისი ამჟამად მიუწვდომელია (კონფიგურაციის ხარვეზი).',
            ];
            return;
        }

        // ამოვაქვთ მომხმარებლის პროფილი
        $profile = StudentProfile::where('user_id', Auth::id())->first();
        
        $avgHours = $profile->avg_study_hours_per_day ?? 2;
        $avgMinutes = $profile->avg_minutes_per_subject ?? 45;
        $hasTutor = ($profile->has_tutor ?? false) ? 'ჰყავს რეპეტიტორი' : 'არ ჰყავს რეპეტიტორი';

        $messages = [[
            'role' => 'system',
            'content' => 'შენ ხარ მოსწავლის პროფესიონალური, მეგობრული და ზრუნველი სასწავლო ასისტენტი. '
                . 'მომხმარებელს ესაუბრე მხოლოდ როგორც მოსწავლეს, თბილად და მხარდამჭერად — არასდროს ახსენო კოდი, პროცესები ან ტექნიკური დეტალები. '
                . 'მომხმარებლის პირადი პროფილი: დღიური მეცადინეობის ლიმიტი: ' . $avgHours . ' საათი, ერთ საგანზე: ' . $avgMinutes . ' წუთი. '
                . 'თუ მომხმარებელი გთხოვს განმეორებადი ან გრძელვადიანი გაკვეთილების ჩანიშვნას (მაგალითად: "ყოველ ხუთშობათს 6 თვის განმავლობაში"), ავტომატურად გამოთვალე შესაბამისი თარიღები. '
                . 'ჩატში დაუწერე მხოლოდ მოკლე, სასიამოვნო დასტური (მაგ: "შენი ინგლისური ენის გაკვეთილები ყოველ ხუთშობათს, 18:00 საათზე მთელი 6 თვის განმავლობაში წარმატებით დავგეგმე და განრიგში დავამატე! 🎓"). '
                . 'პასუხის ბოლოში აუცილებლად დაურთე დაფარული JSON მასივი მკაცრი ფორმატით: '
                . '---JSON:[{"title":"სათაური","description":"აღწერა","type":"tutor","start_time":"YYYY-MM-DD HH:MM:00","end_time":"YYYY-MM-DD HH:MM:00"}]--- '
                . '(`type` შეიძლება იყოს: school_task, tutor, activity, break). მიმდინარე თარიღი და დროა: ' . now()->format('Y-m-d H:i') . '. '
                . 'უპასუხე ქართულად, თბილად და მოკლედ.',
        ]];

        foreach (array_slice($this->chatHistory, -10) as $msg) {
            $messages[] = [
                'role' => $msg['sender'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['text'],
            ];
        }

        try {
            $response = Http::withToken($apiKey)
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])
                ->timeout(60)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => config('services.openrouter.model', 'openai/gpt-4o-mini'),
                    'messages' => $messages,
                ]);

            $reply = $response->successful()
                ? $response->json('choices.0.message.content')
                : null;

            if ($reply) {
                if (preg_match('/---JSON:(.*?)---/s', $reply, $matches)) {
                    $jsonString = trim($matches[1]);
                    $tasksData = json_decode($jsonString, true);

                    if ($tasksData) {
                        if (isset($tasksData['title'])) {
                            $tasksData = [$tasksData];
                        }

                        foreach ($tasksData as $taskData) {
                            if (isset($taskData['title'], $taskData['start_time'], $taskData['end_time'])) {
                                Task::create([
                                    'user_id' => Auth::id(),
                                    'title' => $taskData['title'],
                                    'description' => $taskData['description'] ?? null,
                                    'type' => $taskData['type'] ?? 'school_task',
                                    'start_time' => $taskData['start_time'],
                                    'end_time' => $taskData['end_time'],
                                    'is_completed' => false,
                                ]);
                            }
                        }
                    }

                    $reply = preg_replace('/---JSON:.*?---/s', '', $reply);
                }
            } else {
                logger()->error('OpenRouter error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $reply = 'ბოდიში, კავშირის დროს შეფერხებაა. სცადე თავიდან.';
            }
        } catch (\Throwable $e) {
            logger()->error('OpenRouter exception: ' . $e->getMessage());
            $reply = 'ბოდიში, ტექნიკური შეფერხებაა.';
        }

        $this->chatHistory[] = ['sender' => 'ai', 'text' => trim($reply)];
    }

    public function render()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->orderBy('start_time', 'asc')
            ->get();

        $profile = StudentProfile::where('user_id', Auth::id())->first();

        return view('livewire.dashboard.student-dashboard', [
            'tasks' => $tasks,
            'profile' => $profile,
        ])->layout('layouts.app');
    }
}