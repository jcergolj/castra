<?php

use App\Http\Controllers\Account\EmailController;
use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\VerifyNewEmailController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::namespace('Account')
        ->prefix('account')
        ->name('account.')
        ->group(function () {
            Route::get('profile', [ProfileController::class, '__invoke'])->name('profile');

            Route::get('password/edit', [PasswordController::class, 'edit'])->name('password.edit');
            Route::patch('password', [PasswordController::class, 'update'])->name('password.update');

            Route::get('email/edit', [EmailController::class, 'edit'])->name('email.edit');
            Route::patch('email', [EmailController::class, 'update'])->name('email.update');
        });
});

Route::get('account/verify-email', [VerifyNewEmailController::class, '__invoke'])
    ->middleware('throttle:6,1')
    ->name('account.verification.verify');
