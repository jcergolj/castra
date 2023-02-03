<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestoredItemController;
use App\Http\Controllers\Account\EmailController;
use App\Http\Controllers\Account\ImageController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\PasswordController;
use App\Http\Controllers\Account\VerifyNewEmailController;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboards.index');

    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::post('restored-items', [RestoredItemController::class, 'store'])->name('restored-item.store');

    Route::namespace('Account')
        ->prefix('account')
        ->name('accounts.')
        ->group(function () {
            Route::get('profile', [ProfileController::class, '__invoke'])->name('profile');

            Route::get('password/edit', [PasswordController::class, 'edit'])->name('passwords.edit');
            Route::patch('password', [PasswordController::class, 'update'])->name('passwords.update');

            Route::get('email/edit', [EmailController::class, 'edit'])->name('emails.edit');
            Route::patch('email', [EmailController::class, 'update'])->name('emails.update');

            Route::get('profile-image/edit', [ImageController::class, 'edit'])->name('profile-images.edit');
            Route::patch('profile-image', [ImageController::class, 'update'])->name('profile-images.update');
        });
});

Route::get('account/verify-email', [VerifyNewEmailController::class, '__invoke'])
    ->middleware('throttle:6,1')
    ->name('accounts.verification.verify');
