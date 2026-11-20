<?php

use Illuminate\Support\Facades\Schedule;

// ყოველ წუთს ამოწმებს, რომელ დავალებას დაუდგა დასრულების დრო
Schedule::command('tasks:send-alarms')->everyMinute()->withoutOverlapping();
