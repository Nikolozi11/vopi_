<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Task;
use Minishlink\LaravelWebPush\Facades\WebPush;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ყოველწუთიანი შემოწმება ფუშ-შეტყობინებებისთვის
Schedule::call(function () {
    $now = now()->format('Y-m-d H:i:00');
    
    // ვეძებთ დავალებებს, რომელთა დაწყების დროც ზუსტად ახლანდელია და არ არიან დასრულებული
    $tasks = Task::where('start_time', $now)->where('is_completed', false)->get();

    foreach ($tasks as $task) {
        $user = $task->user;
        
        // ვამოწმებთ, აქვს თუ არა მომხმარებელს დამახსოვრებული ფუშ სუბსქრიფშენი
        if ($user && method_exists($user, 'pushSubscriptions')) {
            foreach ($user->pushSubscriptions as $sub) {
                WebPush::send(
                    $sub,
                    json_encode([
                        'title' => '⏰ სასწავლო შეხსენება!',
                        'body' => "დროა: {$task->title} იწყება ახლა!"
                    ])
                );
            }
        }
    }
})->everyMinute();