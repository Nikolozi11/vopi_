<?php

namespace App\Livewire\Auth;

use App\Livewire\Concerns\ManagesProfileForm;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    use ManagesProfileForm;

    public int $step = 1;

    // ძირითადი ველები
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'student';

    // AI Onboarding ველები
    public int $avg_study_hours_per_day = 2;
    public int $avg_minutes_per_subject = 45;
    public string $school_start_time = '08:30';
    public string $school_end_time = '14:30';
    public array $school_days = [1, 2, 3, 4, 5];

    public bool $has_tutor = false;
    public array $tutors = [];
    public array $activities = [];

    public function mount(): void
    {
        $this->tutors = [$this->blankTutor()];
        $this->activities = [$this->blankActivity()];
    }

    public function selectRole(string $selectedRole)
    {
        $this->role = $selectedRole;
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:student,teacher,parent',
            ]);

            if ($this->role === 'student') {
                $this->step = 2;
            } else {
                return $this->register();
            }
        }
    }

    public function previousStep()
    {
        $this->step = 1;
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:student,teacher,parent',
        ]);

        if ($this->role === 'student') {
            $this->validateProfile();
        }

        $user = DB::transaction(function () {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
            ]);

            if ($this->role === 'student') {
                $tutorRows = $this->tutorRows();

                StudentProfile::create([
                    'user_id' => $user->id,
                    'avg_study_hours_per_day' => $this->avg_study_hours_per_day,
                    'avg_minutes_per_subject' => $this->avg_minutes_per_subject,
                    'has_tutor' => count($tutorRows) > 0,
                    'school_start_time' => $this->school_start_time,
                    'school_end_time' => $this->school_end_time,
                    'school_days' => array_map('intval', $this->school_days),
                ]);

                $user->tutors()->createMany($tutorRows);
                $user->activities()->createMany($this->activityRows());
            }

            return $user;
        });

        Auth::login($user);

        return redirect()->to('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.app');
    }
}
