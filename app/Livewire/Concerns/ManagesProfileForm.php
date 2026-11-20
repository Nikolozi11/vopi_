<?php

namespace App\Livewire\Concerns;

/**
 * Register-ისა და პროფილის გვერდის საერთო ლოგიკა:
 * რეპეტიტორები / წრეები კვირის დღეებითა და საათებით, სკოლის საათები.
 */
trait ManagesProfileForm
{
    protected function blankSlot(): array
    {
        return ['day' => 1, 'start' => '16:00', 'end' => '17:00'];
    }

    protected function blankTutor(): array
    {
        return ['subject' => '', 'schedule' => [$this->blankSlot()]];
    }

    protected function blankActivity(): array
    {
        return ['name' => '', 'schedule' => [$this->blankSlot()]];
    }

    public function addTutorInput(): void
    {
        $this->tutors[] = $this->blankTutor();
    }

    public function removeTutorInput(int $index): void
    {
        unset($this->tutors[$index]);
        $this->tutors = array_values($this->tutors);
    }

    public function addActivityInput(): void
    {
        $this->activities[] = $this->blankActivity();
    }

    public function removeActivityInput(int $index): void
    {
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
    }

    public function addSlot(string $group, int $i): void
    {
        if (! in_array($group, ['tutors', 'activities'], true)) {
            return;
        }
        $items = $this->{$group};
        $items[$i]['schedule'][] = $this->blankSlot();
        $this->{$group} = $items;
    }

    public function removeSlot(string $group, int $i, int $j): void
    {
        if (! in_array($group, ['tutors', 'activities'], true)) {
            return;
        }
        $items = $this->{$group};
        unset($items[$i]['schedule'][$j]);
        $items[$i]['schedule'] = array_values($items[$i]['schedule']);
        $this->{$group} = $items;
    }

    protected function profileRules(): array
    {
        $slot = fn (string $p) => [
            "$p.*.schedule.*.day" => 'integer|between:1,7',
            "$p.*.schedule.*.start" => 'required|date_format:H:i',
            "$p.*.schedule.*.end" => "required|date_format:H:i|after:$p.*.schedule.*.start",
        ];

        return [
            'avg_study_hours_per_day' => 'required|integer|min:1|max:12',
            'avg_minutes_per_subject' => 'required|integer|min:15|max:180',
            'school_start_time' => 'required|date_format:H:i',
            'school_end_time' => 'required|date_format:H:i|after:school_start_time',
            'school_days' => 'array',
            'school_days.*' => 'integer|between:1,7',
            'tutors.*.subject' => 'nullable|string|max:100',
            'activities.*.name' => 'nullable|string|max:100',
        ] + $slot('tutors') + $slot('activities');
    }

    protected function profileMessages(): array
    {
        return [
            'school_end_time.after' => 'სკოლის დასრულება დაწყების შემდეგ უნდა იყოს.',
            'tutors.*.schedule.*.end.after' => 'დასრულება დაწყების შემდეგ უნდა იყოს.',
            'activities.*.schedule.*.end.after' => 'დასრულება დაწყების შემდეგ უნდა იყოს.',
            '*.date_format' => 'არასწორი დროის ფორმატი.',
        ];
    }

    protected function validateProfile(): void
    {
        $this->validate($this->profileRules(), $this->profileMessages());
    }

    protected function cleanSlots(array $slots): array
    {
        $out = [];
        foreach ($slots as $s) {
            if (empty($s['start']) || empty($s['end'])) {
                continue;
            }
            $out[] = [
                'day' => (int) ($s['day'] ?? 1),
                'start' => substr($s['start'], 0, 5),
                'end' => substr($s['end'], 0, 5),
            ];
        }

        return $out;
    }

    /** @return array<int, array> ბაზაში ჩასაწერი რეპეტიტორები */
    protected function tutorRows(): array
    {
        if (! $this->has_tutor) {
            return [];
        }

        $rows = [];
        foreach ($this->tutors as $t) {
            $subject = trim($t['subject'] ?? '');
            if ($subject === '') {
                continue;
            }
            $slots = $this->cleanSlots($t['schedule'] ?? []);
            $hours = $slots
                ? round(array_sum(array_map(fn ($s) => (strtotime($s['end']) - strtotime($s['start'])) / 3600, $slots)) / count($slots) * 2) / 2
                : 1.0;
            $rows[] = [
                'subject' => $subject,
                'schedule' => $slots,
                'sessions_per_week' => max(1, count($slots)),
                'hours_per_session' => min(9.9, max(0.5, $hours)),
            ];
        }

        return $rows;
    }

    /** @return array<int, array> */
    protected function activityRows(): array
    {
        $rows = [];
        foreach ($this->activities as $a) {
            $name = trim($a['name'] ?? '');
            if ($name === '') {
                continue;
            }
            $slots = $this->cleanSlots($a['schedule'] ?? []);
            $rows[] = [
                'name' => $name,
                'schedule' => $slots,
                'days_per_week' => max(1, count(array_unique(array_column($slots, 'day')))),
            ];
        }

        return $rows;
    }
}
