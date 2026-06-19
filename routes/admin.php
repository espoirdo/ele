<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminBookingController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class,'showLogin'])->name('login');
    Route::post('/login',[AdminAuthController::class,'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class,'logout'])->name('logout');

    Route::middleware(['auth','admin'])->group(function () {
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');
        Route::resource('events', AdminEventController::class)->except(['create','store']);
        Route::patch('events/{event}/approve',[AdminEventController::class,'approve'])->name('events.approve');
        Route::patch('events/{event}/reject',[AdminEventController::class,'reject'])->name('events.reject');
        Route::patch('events/{event}/places',[AdminEventController::class,'updatePlaces'])->name('events.updatePlaces');
        Route::resource('users', AdminUserController::class)->only(['index','show','destroy']);
        Route::patch('users/{user}/block',[AdminUserController::class,'block'])->name('users.block');
        Route::patch('users/{user}/promote',[AdminUserController::class,'promote'])->name('users.promote');
        Route::patch('users/{user}/verify',[AdminUserController::class,'verifyEmail'])->name('users.verify');
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('payments', AdminPaymentController::class)->only(['index','show']);
        Route::resource('bookings', AdminBookingController::class)->only(['index','show','update']);
        Route::patch('bookings/{booking}/confirm',[AdminBookingController::class,'confirmPayment'])->name('bookings.confirm');
        Route::patch('bookings/{booking}/status',[AdminBookingController::class,'updateStatus'])->name('bookings.updateStatus');
        Route::resource('comments', AdminCommentController::class)->only(['index','destroy']);
        Route::patch('comments/{comment}/approve',[AdminCommentController::class,'approve'])->name('comments.approve');
        Route::get('settings',[AdminSettingController::class,'index'])->name('settings.index');
        Route::post('settings',[AdminSettingController::class,'update'])->name('settings.update');
    });
});
