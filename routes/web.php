<?php

use App\Livewire\Auth\Register;
use App\Livewire\Dashboard\StudentDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/register', Register::class)->name('register');
Route::get('/dashboard', StudentDashboard::class)->middleware('auth')->name('dashboard');