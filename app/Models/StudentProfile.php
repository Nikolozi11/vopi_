<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avg_study_hours_per_day',
        'avg_minutes_per_subject',
        'extracurricular_activities',
        'has_tutor',
        'tutor_sessions_per_week',
        'tutor_hours_per_session',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}