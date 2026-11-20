<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

/**
 * AI აგენტი: ხედავს მოსწავლის სრულ პროფილს და განრიგს,
 * და tool-ების საშუალებით თვითონ ამატებს / ცვლის / შლის დავალებებს.
 */
class AiPlanner
{
    private const TYPES = ['school_task', 'tutor', 'activity', 'break'];

    public function __construct(private ScheduleService $schedule)
    {
    }

    /**
     * @param array<int, array{sender:string,text:string}> $history ბოლო ელემენტი = ახალი შეტყობინება
     */
    public function chat(User $user, array $history, ?string $imageDataUri = null): string
    {
        $messages = [['role' => 'system', 'content' => $this->systemPrompt($user)]];

        $last = count($history) - 1;
        foreach (array_slice($history, -12, null, true) as $i => $m) {
            $role = $m['sender'] === 'user' ? 'user' : 'assistant';
            if ($imageDataUri && $i === $last && $role === 'user') {
                $messages[] = ['role' => 'user', 'content' => [
                    ['type' => 'text', 'text' => $m['text']],
                    ['type' => 'image_url', 'image_url' => ['url' => $imageDataUri]],
                ]];
            } else {
                $messages[] = ['role' => $role, 'content' => $m['text']];
            }
        }

        for ($round = 0; $round < 6; $round++) {
            $response = Http::withToken(config('services.openrouter.key'))
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                ])
                ->timeout(120)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => config('services.openrouter.model', 'openai/gpt-4o-mini'),
                    'messages' => $messages,
                    'tools' => $this->tools(),
                    'tool_choice' => 'auto',
                ]);

            if (! $response->successful()) {
                logger()->error('OpenRouter error', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \RuntimeException('AI request failed');
            }

            $msg = $response->json('choices.0.message') ?? [];
            $calls = $msg['tool_calls'] ?? [];

            if (! $calls) {
                return trim($msg['content'] ?? '') ?: 'მზადაა!';
            }

            $messages[] = ['role' => 'assistant', 'content' => $msg['content'] ?? null, 'tool_calls' => $calls];

            foreach ($calls as $call) {
                $args = json_decode($call['function']['arguments'] ?? '{}', true) ?: [];
                try {
                    $result = $this->runTool($user, $call['function']['name'], $args);
                } catch (\Throwable $e) {
                    logger()->error('AI tool error: ' . $e->getMessage());
                    $result = ['error' => 'ოპერაცია ვერ შესრულდა: ' . $e->getMessage()];
                }
                $messages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $call['id'],
                    'content' => json_encode($result, JSON_UNESCAPED_UNICODE),
                ];
            }
        }

        return 'ბოდიში, ვერ დავასრულე. სცადე მოკლედ ხელახლა დაწერო.';
    }

    // ------------------------------------------------------------------ prompt

    private function systemPrompt(User $user): string
    {
        $p = $user->studentProfile()->first();
        $days = ScheduleService::DAYS;

        $schoolDays = collect($p->school_days ?? [1, 2, 3, 4, 5])->map(fn ($d) => $days[$d] ?? $d)->implode(', ');
        $school = $p
            ? "{$schoolDays}, " . substr($p->school_start_time, 0, 5) . '–' . substr($p->school_end_time, 0, 5)
            : 'მითითებული არ არის';

        $fmtSlots = fn ($slots) => collect($slots ?? [])
            ->map(fn ($s) => ($days[(int) $s['day']] ?? '?') . " {$s['start']}–{$s['end']}")
            ->implode('; ') ?: 'დრო მითითებული არ არის';

        $tutors = $user->tutors()->get()->map(fn ($t) => "- {$t->subject}: " . $fmtSlots($t->schedule))->implode("\n") ?: '- არ ჰყავს';
        $acts = $user->activities()->get()->map(fn ($a) => "- {$a->name}: " . $fmtSlots($a->schedule))->implode("\n") ?: '- არ აქვს';

        $upcoming = Task::where('user_id', $user->id)
            ->where('end_time', '>=', now()->startOfDay())
            ->where('start_time', '<=', now()->addDays(14)->endOfDay())
            ->orderBy('start_time')->limit(80)->get()
            ->map(fn ($t) => "#{$t->id} | " . $t->start_time->format('Y-m-d H:i') . '–' . $t->end_time->format('H:i') . " | {$t->title} | {$t->type}" . ($t->is_completed ? ' | შესრულებულია' : ''))
            ->implode("\n") ?: '(ცარიელია)';

        $avgHours = $p->avg_study_hours_per_day ?? 2;
        $avgMin = $p->avg_minutes_per_subject ?? 45;
        $now = now();
        $today = $now->format('Y-m-d H:i') . ' (' . $days[$now->dayOfWeekIso] . '), დროის სარტყელი: ' . config('app.timezone');

        return <<<PROMPT
შენ ხარ მოსწავლის თბილი, მეგობრული და ზუსტი სასწავლო ასისტენტი. ესაუბრე ქართულად (თუ მოსწავლე ინგლისურად დაწერს — ინგლისურადაც შეგიძლია). პასუხი იყოს მოკლე. არასდროს ახსენო კოდი, ბაზა, tool-ები ან ტექნიკური დეტალები.

ახლა: {$today}

მოსწავლის პროფილი:
- დღიური მეცადინეობის ლიმიტი: {$avgHours} საათი; ერთ საგანზე: {$avgMin} წუთი
- სკოლა: {$school}
- რეპეტიტორები:
{$tutors}
- წრეები/ჰობი:
{$acts}

უკვე დაგეგმილი დავალებები (მომდევნო 14 დღე):
{$upcoming}

წესები:
1. დავალების ჩასაწერად გამოიყენე add_tasks. თუ მოსწავლე წერს საგანს + გვერდს/ნომერს (მაგ. "მათემატიკა გვ. 45 №3"), სათაური იყოს საგანი, description-ში ჩაწერე გვერდი/ნომერი. თუ დრო არ უთქვამს, თავად შეარჩიე თავისუფალი დრო (იხილე get_schedule) — სკოლის შემდეგ, საღამოს, არაუგვიანეს 22:00-ისა.
2. სკოლის საათებში (და რეპეტიტორის/წრის დროს) არაფერი ჩანიშნო, თუ მოსწავლე თავად პირდაპირ არ მოგთხოვს (მაშინ allow_school_hours=true). არასდროს გადააჭარბო დღიურ ლიმიტს ({$avgHours} სთ) და ერთ საგანზე გამოიყენე დაახლოებით {$avgMin} წუთი; საგნებს შორის დატოვე მცირე შესვენება.
3. თუ tool დააბრუნებს კონფლიქტს, ნუ დაჟინებ — გადაიტანე უახლოეს თავისუფალ დროზე get_schedule-ის მიხედვით, ან ჰკითხე მოსწავლეს. force=true გამოიყენე მხოლოდ თუ მოსწავლემ პირდაპირ თქვა, რომ გადაფარვა არ უშავს.
4. გამეორებადი გაკვეთილები ("ყოველ ხუთშაბათს 6 თვე") — თავად გამოთვალე ყველა თარიღი და ჩაწერე ერთი add_tasks ზარით (მაქს. 200).
5. წასაშლელად/შესაცვლელად გამოიყენე delete_tasks / update_task (id-ები ზემოთ ჩამონათვალშია ან get_schedule-იდან). თუ მოთხოვნა ორაზროვანია (რომელი დავალება?), ჰკითხე.
6. ფოტოზე ხელით ან ბეჭდურად დაწერილი დავალებები (ქართული/ინგლისური) ამოიკითხე და ჩაწერე. თუ თარიღი ფოტოზე არ ჩანს, დავალებები დაგეგმე დღევანდელი დღისთვის (ან ხვალისთვის, თუ დღე უკვე გადის). ბოლოს ჩამოთვალე რა ჩაწერე მოკლედ.
7. ჩატში ბოლოს დაწერე მხოლოდ მოკლე, თბილი დასტური და (საჭიროების შემთხვევაში) რა საათზე დაიგეგმა. არ დააბრუნო JSON.
PROMPT;
    }

    // ------------------------------------------------------------------ tools

    private function tools(): array
    {
        $taskProps = [
            'title' => ['type' => 'string', 'description' => 'საგანი ან დავალების სახელი'],
            'description' => ['type' => 'string', 'description' => 'გვერდი, ნომერი, დეტალები'],
            'type' => ['type' => 'string', 'enum' => self::TYPES],
            'start_time' => ['type' => 'string', 'description' => 'YYYY-MM-DD HH:MM'],
            'end_time' => ['type' => 'string', 'description' => 'YYYY-MM-DD HH:MM'],
        ];

        return [
            ['type' => 'function', 'function' => [
                'name' => 'get_schedule',
                'description' => 'აბრუნებს დაგეგმილ დავალებებს და მუდმივ დაკავებულ დროს (სკოლა, რეპეტიტორი, წრეები) მოცემულ თარიღებს შორის. გამოიყენე თავისუფალი დროის საპოვნელად.',
                'parameters' => ['type' => 'object', 'properties' => [
                    'from' => ['type' => 'string', 'description' => 'YYYY-MM-DD'],
                    'to' => ['type' => 'string', 'description' => 'YYYY-MM-DD (მაქს. 31 დღე)'],
                ], 'required' => ['from', 'to']],
            ]],
            ['type' => 'function', 'function' => [
                'name' => 'add_tasks',
                'description' => 'ამატებს ერთ ან მეტ დავალებას განრიგში. კონფლიქტის შემთხვევაში იმ დავალებას არ ამატებს და კონფლიქტს აბრუნებს.',
                'parameters' => ['type' => 'object', 'properties' => [
                    'tasks' => ['type' => 'array', 'items' => ['type' => 'object', 'properties' => $taskProps + [
                        'allow_school_hours' => ['type' => 'boolean', 'description' => 'true მხოლოდ თუ მოსწავლემ პირდაპირ სთხოვა სკოლის საათებში'],
                        'force' => ['type' => 'boolean', 'description' => 'true მხოლოდ თუ მოსწავლემ პირდაპირ თქვა გადაფარვა არ უშავსო'],
                    ], 'required' => ['title', 'type', 'start_time', 'end_time']]],
                ], 'required' => ['tasks']],
            ]],
            ['type' => 'function', 'function' => [
                'name' => 'update_task',
                'description' => 'ცვლის არსებულ დავალებას id-ით (მხოლოდ გადმოცემულ ველებს).',
                'parameters' => ['type' => 'object', 'properties' => ['id' => ['type' => 'integer']] + $taskProps + [
                    'is_completed' => ['type' => 'boolean'],
                    'allow_school_hours' => ['type' => 'boolean'],
                    'force' => ['type' => 'boolean'],
                ], 'required' => ['id']],
            ]],
            ['type' => 'function', 'function' => [
                'name' => 'delete_tasks',
                'description' => 'შლის დავალებებს id-ების მიხედვით.',
                'parameters' => ['type' => 'object', 'properties' => [
                    'ids' => ['type' => 'array', 'items' => ['type' => 'integer']],
                ], 'required' => ['ids']],
            ]],
        ];
    }

    private function runTool(User $user, string $name, array $a): array
    {
        return match ($name) {
            'get_schedule' => $this->getSchedule($user, $a),
            'add_tasks' => $this->addTasks($user, $a['tasks'] ?? []),
            'update_task' => $this->updateTask($user, $a),
            'delete_tasks' => $this->deleteTasks($user, $a['ids'] ?? []),
            default => ['error' => 'უცნობი ოპერაცია'],
        };
    }

    private function getSchedule(User $user, array $a): array
    {
        $from = Carbon::parse($a['from'])->startOfDay();
        $to = Carbon::parse($a['to'] ?? $a['from'])->endOfDay();
        if ($to->diffInDays($from) > 31) {
            $to = $from->copy()->addDays(31)->endOfDay();
        }

        $tasks = Task::where('user_id', $user->id)
            ->where('start_time', '<=', $to)->where('end_time', '>=', $from)
            ->orderBy('start_time')->limit(200)->get()
            ->map(fn ($t) => [
                'id' => $t->id, 'title' => $t->title, 'type' => $t->type,
                'start' => $t->start_time->format('Y-m-d H:i'), 'end' => $t->end_time->format('H:i'),
                'done' => $t->is_completed,
            ])->all();

        $busy = array_map(fn ($b) => [
            'kind' => $b['kind'], 'label' => $b['label'],
            'start' => $b['start']->format('Y-m-d H:i'), 'end' => $b['end']->format('H:i'),
        ], $this->schedule->blocks($user, $from, $to));

        return ['tasks' => $tasks, 'recurring_busy' => $busy];
    }

    private function addTasks(User $user, array $items): array
    {
        $results = [];

        foreach (array_slice($items, 0, 200) as $item) {
            $results[] = $this->createOne($user, $item);
        }

        return [
            'created' => count(array_filter($results, fn ($r) => isset($r['id']))),
            'results' => $results,
        ];
    }

    private function createOne(User $user, array $t): array
    {
        $title = trim((string) ($t['title'] ?? ''));
        if ($title === '') {
            return ['error' => 'სათაური აკლია'];
        }

        try {
            $start = Carbon::parse($t['start_time']);
            $end = Carbon::parse($t['end_time']);
        } catch (\Throwable) {
            return ['title' => $title, 'error' => 'არასწორი თარიღი'];
        }

        if ($end->lte($start)) {
            return ['title' => $title, 'error' => 'დასრულება უნდა იყოს დაწყების შემდეგ'];
        }

        $type = in_array($t['type'] ?? '', self::TYPES, true) ? $t['type'] : 'school_task';

        if (empty($t['force'])) {
            $conf = $this->schedule->conflicts($user, $start, $end, $type, null, ! empty($t['allow_school_hours']));
            if ($conf) {
                return ['title' => $title, 'start' => $start->format('Y-m-d H:i'), 'conflict_with' => $conf];
            }
        }

        $task = Task::create([
            'user_id' => $user->id,
            'title' => mb_substr($title, 0, 255),
            'description' => isset($t['description']) ? mb_substr((string) $t['description'], 0, 1000) : null,
            'type' => $type,
            'start_time' => $start,
            'end_time' => $end,
            'is_completed' => false,
        ]);

        return ['id' => $task->id, 'title' => $title, 'start' => $start->format('Y-m-d H:i'), 'end' => $end->format('H:i')];
    }

    private function updateTask(User $user, array $a): array
    {
        $task = Task::where('user_id', $user->id)->find($a['id'] ?? 0);
        if (! $task) {
            return ['error' => 'დავალება ვერ მოიძებნა'];
        }

        $data = [];
        foreach (['title', 'description'] as $f) {
            if (array_key_exists($f, $a)) {
                $data[$f] = $a[$f];
            }
        }
        if (isset($a['type']) && in_array($a['type'], self::TYPES, true)) {
            $data['type'] = $a['type'];
        }
        if (isset($a['is_completed'])) {
            $data['is_completed'] = (bool) $a['is_completed'];
        }

        try {
            $start = isset($a['start_time']) ? Carbon::parse($a['start_time']) : $task->start_time;
            $end = isset($a['end_time']) ? Carbon::parse($a['end_time']) : $task->end_time;
        } catch (\Throwable) {
            return ['error' => 'არასწორი თარიღი'];
        }

        if (isset($a['start_time']) || isset($a['end_time'])) {
            if ($end->lte($start)) {
                return ['error' => 'დასრულება უნდა იყოს დაწყების შემდეგ'];
            }
            if (empty($a['force'])) {
                $conf = $this->schedule->conflicts($user, $start, $end, $data['type'] ?? $task->type, $task->id, ! empty($a['allow_school_hours']));
                if ($conf) {
                    return ['id' => $task->id, 'conflict_with' => $conf];
                }
            }
            $data['start_time'] = $start;
            $data['end_time'] = $end;
        }

        $task->update($data);

        return ['updated' => $task->id];
    }

    private function deleteTasks(User $user, array $ids): array
    {
        $ids = array_slice(array_filter(array_map('intval', $ids)), 0, 300);

        return ['deleted' => Task::where('user_id', $user->id)->whereIn('id', $ids)->delete()];
    }
}
