<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\StudentDashboard;
use App\Livewire\Profile\EditProfile;
use App\Console\Commands\SendTaskAlarms;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/profile', EditProfile::class)->name('profile');
});

Route::post('/api/save-subscription', [PushSubscriptionController::class, 'store'])->middleware('auth');

// მაღვიძარის გაჩერება შეტყობინებიდან (service worker-ი მოუხმობს; ტოკენით დაცულია)
Route::get('/alarm/stop/{task}/{token}', function (int $task, string $token) {
    abort_unless(hash_equals(SendTaskAlarms::stopToken($task), $token), 403);
    Task::whereKey($task)->update(['notify_count' => 99]);

    return response()->noContent();
})->name('alarm.stop');
