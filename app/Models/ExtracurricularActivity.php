<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtracurricularActivity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'schedule', 'days_per_week'];

    protected $casts = ['schedule' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
