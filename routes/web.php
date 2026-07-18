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
Route::get('/dashboard', function () {
    return redirect('/events');
})->name('dashboard');

// Newsletter
Route::post('/newsletter/inscription', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/desabonnement', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Auth
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile routes (requires verified email)
Route::middleware(['auth', 'verified', 'vip'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('user.profile');
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.edit');
    Route::patch('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// VIP routes (requires verified email)
Route::middleware(['auth', 'verified', 'vip'])->group(function () {
    Route::get('/vip/souscrire', [\App\Http\Controllers\VipController::class, 'show'])->name('vip.subscribe.show');
    Route::post('/vip/souscrire', [\App\Http\Controllers\VipController::class, 'process'])->name('vip.subscribe.process');
    Route::get('/vip/callback', [\App\Http\Controllers\VipController::class, 'callback'])->name('vip.callback');
});

// Marketplace route (accessible to everyone)
Route::get('/marketplace', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace.index');

// Marketplace - publish (requires VIP)
Route::middleware(['auth', 'verified', 'vip'])->group(function () {
    Route::get('/marketplace/publier', [\App\Http\Controllers\MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [\App\Http\Controllers\MarketplaceController::class, 'store'])->name('marketplace.store');
});

// Authentifié (requires verified email)
Route::middleware(['auth', 'verified', 'check.blocked'])->group(function () {
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

// Public route for running migrations (without auth for quick deployment)
Route::get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();

        // Get migration status
        $migrations = \DB::table('migrations')->orderBy('id', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Database migrated successfully',
            'migrations_applied' => $migrations->pluck('migration')->toArray(),
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Hidden route for running migrations (only for admin) - backup route
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin-run-migrations', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            return response()->json([
                'success' => true,
                'output' => \Illuminate\Support\Facades\Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    Route::get('/run-seeders', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true, '--class' => 'Database\\Seeders\\RolePermissionSeeder']);
            return response()->json([
                'success' => true,
                'output' => \Illuminate\Support\Facades\Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Refresh all migrations (use with caution)
    Route::get('/migrate-fresh', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
            return response()->json([
                'success' => true,
                'output' => \Illuminate\Support\Facades\Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Show migration status
    Route::get('/migration-status', function () {
        try {
            $migrations = \Illuminate\Support\Facades\DB::table('migrations')->get();
            return response()->json([
                'success' => true,
                'migrations' => $migrations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Skip a failed migration
    Route::get('/skip-migration/{name}', function ($name) {
        try {
            \Illuminate\Support\Facades\DB::table('migrations')->where('migration', $name)->delete();
            return response()->json([
                'success' => true,
                'message' => "Migration $name skipped"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Clear config cache
    Route::get('/clear-cache', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            return response()->json([
                'success' => true,
                'output' => \Illuminate\Support\Facades\Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });
});

// Admin routes
require __DIR__ . '/admin.php';
// Auth scaffolding routes (password reset, email verification...)
require __DIR__ . '/auth.php';