<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Account\EmailController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\PasswordController;

require __DIR__.'/auth.php';

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
