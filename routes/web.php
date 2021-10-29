<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Account\ProfileController;

require __DIR__.'/auth.php';

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::namespace('Account')
        ->prefix('account')
        ->name('account.')
        ->group(function () {
            Route::get('profile', [ProfileController::class, '__invoke'])->name('profile');
        });
});
