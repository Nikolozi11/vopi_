<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Tutor;
use App\Models\ExtracurricularActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
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

    // დინამიკური რეპეტიტორები
    public bool $has_tutor = false;
    public array $tutors = [
        ['subject' => '', 'sessions_per_week' => 2, 'hours_per_session' => 1.0]
    ];

    // დინამიკური წრეები / ჰობი
    public array $activities = [
        ['name' => '', 'days_per_week' => 2]
    ];

    public function selectRole(string $selectedRole)
    {
        $this->role = $selectedRole;
    }

    // რეპეტიტორის ინპუტის დამატება
    public function addTutorInput()
    {
        $this->tutors[] = ['subject' => '', 'sessions_per_week' => 2, 'hours_per_session' => 1.0];
    }

    // რეპეტიტორის ინპუტის ამოღება
    public function removeTutorInput($index)
    {
        unset($this->tutors[$index]);
        $this->tutors = array_values($this->tutors);
    }

    // წრის/ჰობის ინპუტის დამატება
    public function addActivityInput()
    {
        $this->activities[] = ['name' => '', 'days_per_week' => 2];
    }

    // წრის/ჰობის ინპუტის ამოღება
    public function removeActivityInput($index)
    {
        unset($this->activities[$index]);
        $this->activities = array_values($this->activities);
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
                $this->register();
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
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        if ($this->role === 'student') {
            StudentProfile::create([
                'user_id' => $user->id,
                'avg_study_hours_per_day' => $this->avg_study_hours_per_day ?? 2,
                'avg_minutes_per_subject' => $this->avg_minutes_per_subject ?? 45,
                'has_tutor' => $this->has_tutor ?? false,
            ]);

            // რეპეტიტორების შენახვა
            if ($this->has_tutor && is_array($this->tutors)) {
                foreach ($this->tutors as $tutor) {
                    if (!empty($tutor['subject'])) {
                        Tutor::create([
                            'user_id' => $user->id,
                            'subject' => $tutor['subject'],
                            'sessions_per_week' => $tutor['sessions_per_week'] ?? 2,
                            'hours_per_session' => $tutor['hours_per_session'] ?? 1.0,
                        ]);
                    }
                }
            }

            // წრეების შენახვა
            if (is_array($this->activities)) {
                foreach ($this->activities as $act) {
                    if (!empty($act['name'])) {
                        ExtracurricularActivity::create([
                            'user_id' => $user->id,
                            'name' => $act['name'],
                            'days_per_week' => $act['days_per_week'] ?? 2,
                        ]);
                    }
                }
            }
        }

        Auth::login($user);

        return redirect()->to('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.app');
    }
}