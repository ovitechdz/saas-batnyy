<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Authentication\Controllers\Web\LoginController;
use App\Modules\Authentication\Controllers\Web\RegisterController;
use App\Modules\Authentication\Controllers\Web\PasswordResetController;
use App\Modules\Authentication\Controllers\Web\MfaController;
use App\Modules\Authentication\Controllers\Web\ProfileController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/mfa/challenge', [MfaController::class, 'showChallengeForm'])->name('mfa.challenge');
    Route::post('/mfa/verify', [MfaController::class, 'verify'])->name('mfa.verify');

    // Profile & Settings
    Route::middleware('require.mfa')->group(function() {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});
