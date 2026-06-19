<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventCreateController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NewsletterController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Newsletter
Route::post('/newsletter/inscription', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Auth
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authentifié
Route::middleware(['auth', 'check.blocked'])->group(function () {
    // Ancien chemin (redirect vers etape 1)
    // Route::get('/events/create', [EventController::class, 'create'])->name('events.create');

    // Nouveau chemin multi-etapes
    Route::get('/evenements/creer', [EventCreateController::class, 'showStep1'])->name('events.create');
    Route::get('/evenements/creer/etape-1', [EventCreateController::class, 'showStep1'])->name('events.create.step1');
    Route::post('/evenements/creer/etape-1', [EventCreateController::class, 'postStep1'])->name('events.create.step1.post');

    Route::get('/evenements/creer/etape-2', [EventCreateController::class, 'showStep2'])->name('events.create.step2');
    Route::post('/evenements/creer/etape-2', [EventCreateController::class, 'postStep2'])->name('events.create.step2.post');

    Route::get('/evenements/creer/etape-3', [EventCreateController::class, 'showStep3'])->name('events.create.step3');
    Route::post('/evenements/creer/etape-3', [EventCreateController::class, 'postStep3'])->name('events.create.step3.post');

    Route::get('/evenements/creer/etape-4', [EventCreateController::class, 'showStep4'])->name('events.create.step4');
    Route::post('/evenements/creer/etape-4', [EventCreateController::class, 'postStep4'])->name('events.create.step4.post');

    // Anciennes routes conservees pour compatibilite
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::post('/events/draft', [EventController::class, 'draft'])->name('events.draft');
    Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/report', [CommentController::class, 'report'])->name('comments.report');

    // Booking routes
    Route::post('/reservation/{event}', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/evenements/{event:slug}/participer', [BookingController::class, 'confirmShow'])->name('booking.confirm.show');
    Route::post('/evenements/{event:slug}/participer', [BookingController::class, 'confirmStore'])->name('booking.confirm.store');
    Route::get('/reservation/confirmation/{booking}', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/mes-reservations', [BookingController::class, 'myBookings'])->name('user.bookings');

    // Payment routes
    Route::get('/paiement/{event:slug}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/paiement/{event:slug}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/paiement/confirmation/{booking}', [PaymentController::class, 'confirmation'])->name('payment.confirmation');
    Route::get('/ticket/telecharger/{booking}', [PaymentController::class, 'downloadTicket'])->name('ticket.download');
});

Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/paiement/callback', [PaymentController::class, 'callback'])->name('payment.callback');

// Pages statiques
Route::get('/news', function () {
    return view('pages.news');
})->name('news');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

// Admin routes
require __DIR__ . '/admin.php';
// Auth scaffolding routes (password reset, email verification...)
require __DIR__ . '/auth.php';