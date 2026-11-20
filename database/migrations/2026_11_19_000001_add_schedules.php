<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // schedule = [{"day":4,"start":"18:00","end":"19:30"}, ...]   day: 1=ორშ ... 7=კვი
        Schema::table('tutors', function (Blueprint $t) {
            $t->json('schedule')->nullable();
        });
        Schema::table('extracurricular_activities', function (Blueprint $t) {
            $t->json('schedule')->nullable();
        });
        Schema::table('student_profiles', function (Blueprint $t) {
            $t->json('school_days')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tutors', fn (Blueprint $t) => $t->dropColumn('schedule'));
        Schema::table('extracurricular_activities', fn (Blueprint $t) => $t->dropColumn('schedule'));
        Schema::table('student_profiles', fn (Blueprint $t) => $t->dropColumn('school_days'));
    }
};
