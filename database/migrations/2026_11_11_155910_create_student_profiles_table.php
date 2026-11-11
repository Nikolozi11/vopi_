<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // AI onboarding კითხვები
            $table->integer('avg_study_hours_per_day')->default(2); // საშუალოდ რამდენ ხანს მეცადინეობს (საათი)
            $table->integer('avg_minutes_per_subject')->default(45); // ერთ საგანზე დახარჯული დრო (წუთი)
            $table->text('extracurricular_activities')->nullable(); // წრეები / ჰობი
            $table->boolean('has_tutor')->default(false); // ემზადება თუ არა რეპეტიტორთან
            $table->integer('tutor_sessions_per_week')->default(0); // კვირაში რამდენჯერ
            $table->decimal('tutor_hours_per_session', 3, 1)->default(1.0); // თითო სესიის ხანგრძლივობა (საათი)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};