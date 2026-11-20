<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('avg_study_hours_per_day')->default(2);
            $table->integer('avg_minutes_per_subject')->default(45);
            $table->boolean('has_tutor')->default(false);
            $table->time('school_start_time')->default('08:30:00');
            $table->time('school_end_time')->default('14:30:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};