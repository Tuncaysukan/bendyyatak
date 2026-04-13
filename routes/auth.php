<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

Route::middleware('guest')->group(function () {
    Route::get('/uye-girisi', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/uye-girisi', [AuthenticatedSessionController::class, 'store']);

    Route::get('/uye-ol', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/uye-ol', [RegisteredUserController::class, 'store']);

    Route::get('/sifremi-unuttum', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/sifremi-unuttum', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/sifre-sifirla/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/sifre-sifirla', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/cikis', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
