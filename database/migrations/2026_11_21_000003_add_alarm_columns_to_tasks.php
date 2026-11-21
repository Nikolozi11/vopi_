<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $t) {
            $t->timestamp('start_notified_at')->nullable();      // დაწყების შეხსენება გაიგზავნა
            $t->unsignedSmallInteger('notify_count')->default(0); // დასრულების შეხსენების გამეორებები (99 = გაჩერებულია)
        });
    }

    public function down(): void
    {
        Schema::table('tasks', fn (Blueprint $t) => $t->dropColumn(['start_notified_at', 'notify_count']));
    }
};
