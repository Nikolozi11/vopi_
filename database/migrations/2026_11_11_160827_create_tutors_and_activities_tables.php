<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // რეპეტიტორების ცხრილი (რამდენიმე რეპეტიტორისთვის)
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject'); // საგანი (მაგ: მათემატიკა)
            $table->integer('sessions_per_week'); // კვირაში რამდენჯერ
            $table->decimal('hours_per_session', 3, 1); // ხანგრძლივობა (საათი)
            $table->timestamps();
        });

        // წრეების/ჰობის ცხრილი (სათითაოდ დამატებისთვის)
        Schema::create('extracurricular_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // წრის დასახელება (მაგ: გიტარა, ცურვა)
            $table->integer('days_per_week')->default(1); // კვირაში რამდენჯერ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
        Schema::dropIfExists('extracurricular_activities');
    }
};