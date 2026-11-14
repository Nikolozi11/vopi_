<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\StudentDashboard;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Auth;

// მთავარი გვერდი: თუ უკვე შესულია, გადაამისამართებს დეშბორდზე
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return app(Login::class);
});

// რეგისტრაციის გვერდი: თუ უკვე შესულია, გადაამისამართებს დეშბორდზე
Route::get('/register', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return app(Register::class);
})->name('register');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/dashboard', StudentDashboard::class)->middleware('auth')->name('dashboard');

Route::post('/api/save-subscription', [PushSubscriptionController::class, 'store'])->middleware('auth');