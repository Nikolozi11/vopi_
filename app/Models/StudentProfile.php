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
        'has_tutor',
        'school_start_time',
        'school_end_time',
        'school_days',
    ];

    protected $casts = [
        'has_tutor' => 'boolean',
        'school_days' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
