<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\StudentDashboard;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

Route::get('/reset-database', function () {
    // ვშლით ბაზაში არსებულ ყველა ცხრილს
    Artisan::call('db:wipe', ['--force' => true]);
    
    // ვუშვებთ მიგრაციებს თავიდან
    Artisan::call('migrate', ['--force' => true]);
    
    return '<h3>ბაზა სრულად გასუფთავდა და მიგრაციები თავიდან გაეშვა!</h3>';
});

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