<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\SmtpListingController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Middleware\AdminAccess;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', AdminAccess::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
    
    // Users Management
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::post('users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::post('users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
    
    // Vendors Management
    Route::resource('vendors', VendorController::class)->except(['create', 'store', 'destroy']);
    Route::post('vendors/{vendor}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
    Route::post('vendors/{vendor}/reject', [VendorController::class, 'reject'])->name('vendors.reject');
    Route::post('vendors/{vendor}/suspend', [VendorController::class, 'suspend'])->name('vendors.suspend');
    Route::post('vendors/{vendor}/activate', [VendorController::class, 'activate'])->name('vendors.activate');
    
    // Vendor Listings
    Route::get('vendors-listings', [VendorController::class, 'listings'])->name('vendors.listings');
    Route::post('vendors-listings/{listing}/approve', [VendorController::class, 'approveListing'])->name('vendors.listings.approve');
    Route::post('vendors-listings/{listing}/reject', [VendorController::class, 'rejectListing'])->name('vendors.listings.reject');
    
    // SMTP Accounts
    Route::resource('smtp', SmtpListingController::class)->except(['create', 'store']);
    Route::post('smtp/{smtp}/toggle', [SmtpListingController::class, 'toggleStatus'])->name('smtp.toggle');
    Route::post('smtp/{smtp}/verify', [SmtpListingController::class, 'setVerified'])->name('smtp.verify');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    
    Route::get('settings/appearance', [SettingsController::class, 'appearance'])->name('settings.appearance');
    Route::post('settings/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.appearance.update');
    
    Route::get('settings/email', [SettingsController::class, 'email'])->name('settings.email');
    Route::post('settings/email', [SettingsController::class, 'updateEmail'])->name('settings.email.update');
    
    Route::get('settings/logs', [SettingsController::class, 'logs'])->name('settings.logs');
    Route::get('settings/logs/{filename}', [SettingsController::class, 'viewLog'])->name('settings.logs.view');
    Route::post('settings/logs/{filename}/clear', [SettingsController::class, 'clearLog'])->name('settings.logs.clear');
});
