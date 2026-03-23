<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.index')->name('home');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/features', 'features')->name('features');
Route::view('/contact', 'contact')->name('contact');
Route::view('/about', 'about')->name('about');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/terms', 'terms')->name('terms');
Route::view('/security', 'security')->name('security');
Route::view('/refund', 'refund')->name('refund');
Route::view('/help-center', 'help-center')->name('help.center');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/track/open/{id}.png', [TrackingController::class, 'openById'])->name('track.open.id');
Route::get('/track/click/{id}', [TrackingController::class, 'clickById'])->name('track.click.id');
Route::post('/track/bounce', [TrackingController::class, 'trackBounce'])->name('track.bounce');
Route::get('/track/unsubscribe', [TrackingController::class, 'unsubscribe'])->name('track.unsubscribe');

Route::middleware('auth')->group(function (): void {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    Route::post('/billing/checkout', [PaymentController::class, 'createOrder'])->name('billing.checkout')->middleware('throttle:10,1');
    Route::post('/billing/webhook/paypal', [PaymentController::class, 'webhook'])->name('billing.webhook.paypal');
    Route::get('/billing/cancel', [PaymentController::class, 'cancel'])->name('billing.cancel');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
});

Route::middleware(['auth', 'paid'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('campaigns', CampaignController::class);
    Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
    Route::get('/campaigns/{campaign}/preview', [CampaignController::class, 'preview'])->name('campaigns.preview');
    Route::get('/campaigns/{campaign}/analytics', [CampaignController::class, 'analytics'])->name('campaigns.analytics');

    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers', [SubscriberController::class, 'store'])->name('subscribers.store');
    Route::post('/subscribers/import', [SubscriberController::class, 'import'])->name('subscribers.import');
    Route::get('/subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::get('/subscribers/{id}/edit', [SubscriberController::class, 'edit'])->name('subscribers.edit');
    Route::put('/subscribers/{id}', [SubscriberController::class, 'update'])->name('subscribers.update');
    Route::delete('/subscribers/{id}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::post('/lists', [ListController::class, 'store'])->name('lists.store');
    Route::put('/lists/{id}', [ListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{id}', [ListController::class, 'destroy'])->name('lists.destroy');

    Route::resource('messages', MessageController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::resource('smtp', SmtpController::class);
    Route::post('/smtp/{smtp}/test', [SmtpController::class, 'test'])->name('smtp.test');
    Route::post('/smtp/{smtp}/verify', [SmtpController::class, 'verify'])->name('smtp.verify');
    Route::post('/smtp/{smtp}/set-default', [SmtpController::class, 'setDefault'])->name('smtp.set-default');
    Route::get('/api/smtp/credentials', [SmtpController::class, 'getCredentials'])->name('api.smtp.credentials');

    Route::prefix('profile')->name('profile.')->group(function (): void {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    });

    Route::prefix('analytics')->name('analytics.')->group(function (): void {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/campaigns', [AnalyticsController::class, 'campaigns'])->name('campaigns');
        Route::get('/subscribers', [AnalyticsController::class, 'subscribers'])->name('subscribers');
        Route::get('/reports', [AnalyticsController::class, 'reports'])->name('reports');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });
});
