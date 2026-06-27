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
use App\Http\Controllers\Admin\Settings\ContentController;
use App\Http\Controllers\Admin\Settings\LogoController;
use App\Http\Controllers\Admin\Settings\AdminController;

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

        // Parametres etendus - necessite parametres.manage
        Route::middleware(['permission:parametres.manage'])->group(function () {
            // Parametres - Contenu du site
            Route::get('parametres/contenu', [ContentController::class, 'index'])->name('settings.content');
            Route::post('parametres/contenu', [ContentController::class, 'update'])->name('settings.content.update');

            // Parametres - Logos et images
            Route::get('parametres/logos', [LogoController::class, 'index'])->name('settings.logos');
            Route::post('parametres/logos', [LogoController::class, 'update'])->name('settings.logos.update');

            // Parametres - Administrateurs
            Route::get('parametres/administrateurs', [AdminController::class, 'index'])->name('settings.admins');
            Route::post('parametres/administrateurs', [AdminController::class, 'store'])->name('settings.admins.store');
            Route::patch('parametres/administrateurs/{user}/toggle', [AdminController::class, 'toggle'])->name('settings.admins.toggle');
            Route::delete('parametres/administrateurs/{user}', [AdminController::class, 'destroy'])->name('settings.admins.destroy');
        });
    });
});
