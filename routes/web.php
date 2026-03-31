<?php

use App\Http\Controllers\SmtpController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WarmupController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\GDPRController;
use App\Http\Controllers\WebhookController;

// Landing
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirect to onboarding if not completed
    if (!$user || !$user->hasCompletedOnboarding()) {
        return redirect()->route('onboarding.welcome');
    }

    // Redirect admin users to admin dashboard
    if ($user->isAdmin()) {
        return redirect()->route('admin.users');
    }

    // Get dashboard stats
    $stats = [
        'total_campaigns' => $user->campaigns()->count(),
        'total_subscribers' => $user->subscribers()->count(),
        'emails_sent' => $user->campaigns()->sum('sent_count'),
        'open_rate' => $user->campaigns()->sum('sent_count') > 0
            ? round(($user->campaigns()->sum('opened_count') / $user->campaigns()->sum('sent_count')) * 100, 1)
            : 0,
        'recent_campaigns' => $user->campaigns()->latest()->limit(5)->get(),
    ];

    return view('dashboard', compact('stats'));
})->middleware(['auth'])->name('dashboard');

// Onboarding Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding/welcome', [OnboardingController::class, 'welcome'])->name('onboarding.welcome');
    Route::get('/onboarding/business', [OnboardingController::class, 'business'])->name('onboarding.business');
    Route::post('/onboarding/business', [OnboardingController::class, 'storeBusiness'])->name('onboarding.store-business');
    Route::get('/onboarding/smtp', [OnboardingController::class, 'smtp'])->name('onboarding.smtp');
    Route::post('/onboarding/smtp', [OnboardingController::class, 'storeSmtp'])->name('onboarding.store-smtp');
    Route::post('/onboarding/smtp/skip', [OnboardingController::class, 'skipSmtp'])->name('onboarding.skip-smtp');
    Route::get('/onboarding/campaign', [OnboardingController::class, 'campaign'])->name('onboarding.campaign');
    Route::post('/onboarding/campaign', [OnboardingController::class, 'storeCampaign'])->name('onboarding.store-campaign');
    Route::post('/onboarding/campaign/skip', [OnboardingController::class, 'skipCampaign'])->name('onboarding.skip-campaign');
    Route::get('/onboarding/complete', [OnboardingController::class, 'complete'])->name('onboarding.complete');
    Route::get('/onboarding/progress', [OnboardingController::class, 'progress'])->name('onboarding.progress');
});

// SMTP Account Routes (Personal)
Route::prefix('smtp')->middleware(['auth'])->group(function () {
    Route::get('/', [SmtpController::class, 'index'])->name('smtp.index');
    Route::get('/create', [SmtpController::class, 'create'])->name('smtp.create');
    Route::post('/', [SmtpController::class, 'store'])->name('smtp.store');
    Route::get('/{id}/edit', [SmtpController::class, 'edit'])->name('smtp.edit');
    Route::put('/{id}', [SmtpController::class, 'update'])->name('smtp.update');
    Route::delete('/{id}', [SmtpController::class, 'destroy'])->name('smtp.destroy');
    Route::post('/{id}/set-default', [SmtpController::class, 'setDefault'])->name('smtp.set-default');
    Route::post('/{id}/toggle-active', [SmtpController::class, 'toggleActive'])->name('smtp.toggle-active');
    Route::post('/{id}/test', [SmtpController::class, 'test'])->name('smtp.test');
    Route::post('/test-config', [SmtpController::class, 'testConfig'])->name('smtp.test-config');
});

// Marketplace Routes
Route::prefix('marketplace')->group(function () {
    Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/{id}', [MarketplaceController::class, 'show'])->name('marketplace.show');
    Route::get('/{id}/purchase', [MarketplaceController::class, 'showPurchase'])->name('marketplace.purchase.form')->middleware('auth');
    Route::post('/{id}/favorite', [MarketplaceController::class, 'toggleFavorite'])->name('marketplace.favorite')->middleware('auth');
    Route::post('/{id}/purchase', [MarketplaceController::class, 'purchase'])->name('marketplace.purchase')->middleware('auth');
    Route::get('/purchases', [MarketplaceController::class, 'purchases'])->name('marketplace.purchases')->middleware('auth');
});

// Vendor Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/vendor/register', function () { return view('vendor.register'); })->name('vendor.register.form');
    Route::post('/vendor/register', [VendorController::class, 'register'])->name('vendor.register');
});

Route::prefix('vendor')->middleware(['auth', 'role:vendor,admin'])->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::get('/listings', function () { return view('vendor.listings'); })->name('vendor.listings');
    Route::get('/listing/create', [VendorController::class, 'createListing'])->name('vendor.listing-create');
    Route::post('/listing', [VendorController::class, 'storeListing'])->name('vendor.listing-store');
    Route::get('/listing/{id}/edit', [VendorController::class, 'editListing'])->name('vendor.listing-edit');
    Route::put('/listing/{id}', [VendorController::class, 'updateListing'])->name('vendor.listing-update');
    Route::delete('/listing/{id}', [VendorController::class, 'deleteListing'])->name('vendor.listing-delete');
    Route::get('/earnings', [VendorController::class, 'earnings'])->name('vendor.earnings');
    Route::post('/payout', [VendorController::class, 'requestPayout'])->name('vendor.payout');
    Route::get('/settings', [VendorController::class, 'settings'])->name('vendor.settings');
    Route::put('/settings', [VendorController::class, 'updateSettings'])->name('vendor.settings-update');
});

// Campaign Routes
Route::prefix('campaigns')->middleware(['auth'])->group(function () {
    Route::get('/', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');
    Route::post('/{campaign}/pause', [CampaignController::class, 'pause'])->name('campaigns.pause');
    Route::post('/{campaign}/resume', [CampaignController::class, 'resume'])->name('campaigns.resume');
    Route::post('/{campaign}/cancel', [CampaignController::class, 'cancel'])->name('campaigns.cancel');
    Route::post('/{campaign}/duplicate', [CampaignController::class, 'duplicate'])->name('campaigns.duplicate');
    Route::get('/{campaign}/stats', [CampaignController::class, 'stats'])->name('campaigns.stats');
});

// Email Template Routes
Route::prefix('templates')->middleware(['auth'])->group(function () {
    Route::get('/', function () { return view('templates.index'); })->name('templates.index');
    Route::get('/create', function () { return view('templates.create'); })->name('templates.create');
});

// Subscriber Routes
Route::prefix('subscribers')->middleware(['auth'])->group(function () {
    Route::get('/', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('/create', [SubscriberController::class, 'create'])->name('subscribers.create');
    Route::post('/', [SubscriberController::class, 'store'])->name('subscribers.store');
    Route::get('/lists', [SubscriberController::class, 'lists'])->name('subscribers.lists');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Analytics Routes
Route::prefix('analytics')->middleware(['auth'])->group(function () {
    Route::get('/', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/overview', [AnalyticsController::class, 'overview'])->name('analytics.overview');
    Route::get('/campaigns', [AnalyticsController::class, 'campaigns'])->name('analytics.campaigns');
    Route::get('/smtp', [AnalyticsController::class, 'smtp'])->name('analytics.smtp');
    Route::get('/realtime', [AnalyticsController::class, 'realtime'])->name('analytics.realtime');
    Route::get('/chart', [AnalyticsController::class, 'chart'])->name('analytics.chart');
});

// Tracking Routes (Public)
Route::prefix('tracking')->group(function () {
    Route::get('/open', [TrackingController::class, 'open'])->name('tracking.open');
    Route::get('/click/{hash}', [TrackingController::class, 'click'])->name('tracking.click');
    Route::post('/bounce', [TrackingController::class, 'bounce'])->name('tracking.bounce');
    Route::get('/unsubscribe', [TrackingController::class, 'unsubscribe'])->name('tracking.unsubscribe');
});

// Warmup Routes
Route::prefix('warmup')->middleware(['auth'])->group(function () {
    Route::get('/', [WarmupController::class, 'index'])->name('warmup.index');
    Route::get('/{warmup}', [WarmupController::class, 'show'])->name('warmup.show');
    Route::post('/start', [WarmupController::class, 'start'])->name('warmup.start');
    Route::post('/{warmup}/pause', [WarmupController::class, 'pause'])->name('warmup.pause');
    Route::post('/{warmup}/resume', [WarmupController::class, 'resume'])->name('warmup.resume');
    Route::post('/{warmup}/stop', [WarmupController::class, 'stop'])->name('warmup.stop');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', function () { return view('admin.users'); })->name('admin.users');
    Route::get('/roles', function () { return view('admin.roles'); })->name('admin.roles');
    Route::get('/settings', function () { return view('admin.settings'); })->name('admin.settings');
    Route::get('/marketplace', function () { return view('admin.marketplace'); })->name('admin.marketplace');
    Route::get('/vendors', function () { return view('admin.vendors'); })->name('admin.vendors');
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Billing Routes
Route::prefix('billing')->middleware(['auth'])->group(function () {
    Route::get('/', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/plans', [BillingController::class, 'plans'])->name('billing.plans');
    Route::get('/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::post('/change-plan', [BillingController::class, 'changePlan'])->name('billing.change-plan');
    Route::post('/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('billing.invoices');
    Route::get('/invoices/{invoice}', [BillingController::class, 'invoice'])->name('billing.invoice');
    Route::get('/credits', [BillingController::class, 'credits'])->name('billing.credits');
    Route::post('/credits/purchase', [BillingController::class, 'purchaseCredits'])->name('billing.credits.purchase');
});

// GDPR Compliance Routes
Route::prefix('gdpr')->middleware(['auth'])->group(function () {
    Route::get('/', [GDPRController::class, 'dataPortability'])->name('gdpr.index');
    Route::post('/export', [GDPRController::class, 'exportData'])->name('gdpr.export');
    Route::get('/download/{filename}', [GDPRController::class, 'downloadExport'])->name('gdpr.download');
    Route::post('/delete', [GDPRController::class, 'requestDeletion'])->name('gdpr.delete');
    Route::post('/cancel-delete', [GDPRController::class, 'cancelDeletion'])->name('gdpr.cancel-delete');
});

// Stripe Webhooks (no auth required)
Route::post('/webhooks/stripe', [App\Http\Controllers\WebhookController::class, 'handleStripeWebhook']);

require __DIR__.'/auth.php';

// Admin Routes
require __DIR__.'/admin.php';

// Billing Routes
Route::prefix('billing')->middleware(['auth'])->group(function () {
    Route::get('/', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/plans', [BillingController::class, 'plans'])->name('billing.plans');
    Route::get('/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::post('/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::post('/change-plan', [BillingController::class, 'changePlan'])->name('billing.change-plan');
    Route::post('/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
    Route::get('/invoices', [BillingController::class, 'invoices'])->name('billing.invoices');
    Route::get('/invoices/{invoice}', [BillingController::class, 'invoice'])->name('billing.invoice');
    Route::get('/credits', [BillingController::class, 'credits'])->name('billing.credits');
    Route::post('/credits/purchase', [BillingController::class, 'purchaseCredits'])->name('billing.credits.purchase');
});
