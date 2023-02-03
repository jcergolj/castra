<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeletedUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserEmailController;
use App\Http\Controllers\Admin\UserImageController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboards.index');

        Route::resource('users', UserController::class)->except(['edit', 'update']);

        Route::get('user-images/{user}/edit', [UserImageController::class, 'edit'])->name('user-images.edit');
        Route::patch('user-images/{user}/update', [UserImageController::class, 'update'])->name('user-images.update');

        Route::get('user-email/{user}/edit', [UserEmailController::class, 'edit'])->name('user-emails.edit');
        Route::patch('user-email/{user}/update', [UserEmailController::class, 'update'])->name('user-emails.update');
    });
