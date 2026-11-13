<?php

use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\StudentDashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Http\Controllers\PushSubscriptionController;


Route::get('/', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
Route::get('/dashboard', StudentDashboard::class)->middleware('auth')->name('dashboard');

Route::post('/api/save-subscription', [PushSubscriptionController::class, 'store'])->middleware('auth');
