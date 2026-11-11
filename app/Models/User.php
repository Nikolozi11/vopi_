<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Minishlink\LaravelWebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function tutors()
{
    return $this->hasMany(Tutor::class);
}

public function activities()
{
    return $this->hasMany(ExtracurricularActivity::class);
}
}