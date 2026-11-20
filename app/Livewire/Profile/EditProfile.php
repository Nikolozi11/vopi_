<?php

namespace App\Livewire\Profile;

use App\Livewire\Concerns\ManagesProfileForm;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditProfile extends Component
{
    use ManagesProfileForm;

    public int $avg_study_hours_per_day = 2;
    public int $avg_minutes_per_subject = 45;
    public string $school_start_time = '08:30';
    public string $school_end_time = '14:30';
    public array $school_days = [1, 2, 3, 4, 5];

    public bool $has_tutor = false;
    public array $tutors = [];
    public array $activities = [];

    public bool $saved = false;

    public function mount(): void
    {
        $user = Auth::user();
        $p = StudentProfile::where('user_id', $user->id)->first();

        if ($p) {
            $this->avg_study_hours_per_day = $p->avg_study_hours_per_day;
            $this->avg_minutes_per_subject = $p->avg_minutes_per_subject;
            $this->school_start_time = substr($p->school_start_time, 0, 5);
            $this->school_end_time = substr($p->school_end_time, 0, 5);
            $this->school_days = array_map('strval', $p->school_days ?? [1, 2, 3, 4, 5]);
        }

        $this->tutors = $user->tutors()->get()->map(fn ($t) => [
            'subject' => $t->subject,
            'schedule' => $t->schedule ?: [$this->blankSlot()],
        ])->all();

        $this->has_tutor = count($this->tutors) > 0;
        if (! $this->tutors) {
            $this->tutors = [$this->blankTutor()];
        }

        $this->activities = $user->activities()->get()->map(fn ($a) => [
            'name' => $a->name,
            'schedule' => $a->schedule ?: [$this->blankSlot()],
        ])->all();
        if (! $this->activities) {
            $this->activities = [$this->blankActivity()];
        }
    }

    public function save(): void
    {
        $this->saved = false;
        $this->validateProfile();

        $user = Auth::user();

        DB::transaction(function () use ($user) {
            $tutorRows = $this->tutorRows();

            StudentProfile::updateOrCreate(['user_id' => $user->id], [
                'avg_study_hours_per_day' => $this->avg_study_hours_per_day,
                'avg_minutes_per_subject' => $this->avg_minutes_per_subject,
                'has_tutor' => count($tutorRows) > 0,
                'school_start_time' => $this->school_start_time,
                'school_end_time' => $this->school_end_time,
                'school_days' => array_map('intval', $this->school_days),
            ]);

            $user->tutors()->delete();
            $user->tutors()->createMany($tutorRows);

            $user->activities()->delete();
            $user->activities()->createMany($this->activityRows());
        });

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.profile.edit-profile')->layout('layouts.app');
    }
}
