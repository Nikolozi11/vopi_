<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

/**
 * ამზადებს მოსწავლის "დაკავებულ" ბლოკებს (სკოლა, რეპეტიტორი, წრეები)
 * და ამოწმებს ახალი დავალების კონფლიქტებს.
 */
class ScheduleService
{
    public const DAYS = [
        1 => 'ორშაბათი', 2 => 'სამშაბათი', 3 => 'ოთხშაბათი', 4 => 'ხუთშაბათი',
        5 => 'პარასკევი', 6 => 'შაბათი', 7 => 'კვირა',
    ];

    private array $memo = [];

    private function sources(User $user): array
    {
        return $this->memo[$user->id] ??= [
            'profile' => $user->studentProfile()->first(),
            'tutors' => $user->tutors()->get(),
            'activities' => $user->activities()->get(),
        ];
    }

    private function block(string $kind, string $label, Carbon $day, string $start, string $end): array
    {
        return [
            'kind' => $kind,
            'label' => $label,
            'start' => $day->copy()->setTimeFromTimeString($start),
            'end' => $day->copy()->setTimeFromTimeString($end),
        ];
    }

    /** @return array<int, array{kind:string,label:string,start:Carbon,end:Carbon}> */
    public function blocks(User $user, Carbon $from, Carbon $to): array
    {
        $src = $this->sources($user);
        $profile = $src['profile'];
        $out = [];

        for ($d = $from->copy()->startOfDay(); $d->lte($to); $d->addDay()) {
            $dow = $d->dayOfWeekIso;

            if ($profile && in_array($dow, $profile->school_days ?? [1, 2, 3, 4, 5], true)) {
                $out[] = $this->block('school', 'სკოლა', $d,
                    substr($profile->school_start_time ?? '08:30', 0, 5),
                    substr($profile->school_end_time ?? '14:30', 0, 5));
            }
            foreach ($src['tutors'] as $t) {
                foreach ($t->schedule ?? [] as $s) {
                    if ((int) $s['day'] === $dow) {
                        $out[] = $this->block('tutor', $t->subject . ' (რეპეტიტორი)', $d, $s['start'], $s['end']);
                    }
                }
            }
            foreach ($src['activities'] as $a) {
                foreach ($a->schedule ?? [] as $s) {
                    if ((int) $s['day'] === $dow) {
                        $out[] = $this->block('activity', $a->name . ' (წრე)', $d, $s['start'], $s['end']);
                    }
                }
            }
        }

        usort($out, fn ($x, $y) => $x['start'] <=> $y['start']);

        return $out;
    }

    /**
     * @return string[] კონფლიქტების აღწერები (ცარიელი = თავისუფალია)
     */
    public function conflicts(
        User $user,
        Carbon $start,
        Carbon $end,
        string $type,
        ?int $ignoreTaskId = null,
        bool $allowSchool = false,
    ): array {
        $msgs = [];

        foreach ($this->blocks($user, $start, $end) as $b) {
            if ($b['kind'] === 'school' && $allowSchool) {
                continue;
            }
            // რეპეტიტორის/წრის ჩანაწერი თავისივე ბლოკს არ ეჯახება
            if ($b['kind'] === $type) {
                continue;
            }
            if ($start->lt($b['end']) && $end->gt($b['start'])) {
                $msgs[] = $b['label'] . ' ' . $b['start']->format('H:i') . '–' . $b['end']->format('H:i');
            }
        }

        $overlaps = Task::where('user_id', $user->id)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->when($ignoreTaskId, fn ($q) => $q->where('id', '!=', $ignoreTaskId))
            ->get();

        foreach ($overlaps as $t) {
            $msgs[] = '"' . $t->title . '" ' . $t->start_time->format('H:i') . '–' . $t->end_time->format('H:i') . ' (#' . $t->id . ')';
        }

        return $msgs;
    }
}
