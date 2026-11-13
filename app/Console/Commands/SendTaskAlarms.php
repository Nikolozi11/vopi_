<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Carbon\Carbon;

class SendTaskAlarms extends Command
{
    protected $signature = 'tasks:send-alarms';
    protected $description = 'Sends push alarms when student tasks are starting';

    public function handle()
    {
        $now = Carbon::now()->format('Y-m-d H:i');

        // ვეძებთ დავალებებს, რომელთა დაწყების დრო ზუსტად ახლანდელ წუთს ემთხვევა
        $tasks = Task::where('start_time', 'LIKE', "%{$now}%")->get();

        foreach ($tasks as $task) {
            $user = $task->user; // ვისი დავალებაცაა
            
            if (!$user) continue;

            $auth = [
                'VAPID' => [
                    'subject' => 'mailto:admin@vopi.ge',
                    'publicKey' => env('VAPID_PUBLIC_KEY'),
                    'privateKey' => env('VAPID_PRIVATE_KEY'),
                ],
            ];

            $webPush = new WebPush($auth);

            // ვუვლით ამ მომხმარებლის ყველა შენახულ სუბსქრიფშენს (მოწყობილობას)
            foreach ($user->pushSubscriptions as $sub) {
                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'keys' => [
                        'p256dh' => $sub->keys_p256dh,
                        'auth' => $sub->keys_auth,
                    ],
                ]);

                $webPush->queueNotification(
                    $subscription,
                    json_encode([
                        'title' => '🚨 მაღვიძარა: ' . $task->title,
                        'body' => 'შენი ჩანიშნული გაკვეთილი/დავალება იწყება ახლა!',
                    ])
                );
            }

            foreach ($webPush->flush() as $report) {
                // შეტყობინება გაგზავნილია
            }
        }

        $this->info('Task alarms checked and sent successfully.');
    }
}