<?php

use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
        });
});
