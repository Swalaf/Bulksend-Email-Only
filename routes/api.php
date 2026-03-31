<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes (if needed)

// Authenticated API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Campaign API
    Route::apiResource('campaigns', \App\Http\Controllers\Api\CampaignController::class);
    Route::post('campaigns/{campaign}/send', [\App\Http\Controllers\Api\CampaignController::class, 'send']);
    Route::post('campaigns/{campaign}/duplicate', [\App\Http\Controllers\Api\CampaignController::class, 'duplicate']);
    
    // Subscriber API
    Route::apiResource('subscribers', \App\Http\Controllers\Api\SubscriberController::class);
    Route::apiResource('subscriber-lists', \App\Http\Controllers\Api\SubscriberListController::class);
    Route::post('subscribers/import', [\App\Http\Controllers\Api\SubscriberController::class, 'import']);
    Route::post('subscribers/unsubscribe', [\App\Http\Controllers\Api\SubscriberController::class, 'unsubscribe']);
    
    // SMTP Account API
    Route::apiResource('smtp-accounts', \App\Http\Controllers\Api\SmtpAccountController::class);
    
    // Analytics API
    Route::get('/analytics/campaign/{campaignId}', [\App\Http\Controllers\Api\AnalyticController::class, 'campaignAnalytics']);
    Route::get('/analytics/user', [\App\Http\Controllers\Api\AnalyticController::class, 'userAnalytics']);
    Route::get('/analytics/summary', [\App\Http\Controllers\Api\AnalyticController::class, 'summary']);
    Route::get('/analytics/performance', [\App\Http\Controllers\Api\AnalyticController::class, 'performanceOverTime']);
    Route::get('/analytics/top-campaigns', [\App\Http\Controllers\Api\AnalyticController::class, 'topCampaigns']);
    Route::post('/analytics/track', [\App\Http\Controllers\Api\AnalyticController::class, 'track']);
    Route::post('/analytics/export', [\App\Http\Controllers\Api\AnalyticController::class, 'export']);
    
    // AI API
    Route::get('/ai/status', [AiController::class, 'status']);
    Route::post('/ai/generate-content', [AiController::class, 'generateContent']);
    Route::post('/ai/generate-subjects', [AiController::class, 'generateSubjectLines']);
    Route::post('/ai/generate-ab-test', [AiController::class, 'generateAbTest']);
    Route::post('/ai/suggest-improvements', [AiController::class, 'suggestImprovements']);
    Route::post('/ai/generate-template', [AiController::class, 'generateTemplate']);
    Route::post('/ai/analyze-performance', [AiController::class, 'analyzePerformance']);
    Route::post('/ai/rewrite', [AiController::class, 'rewrite']);
    
    // Admin API (Admin only)
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
        Route::apiResource('roles', \App\Http\Controllers\Api\RoleController::class);
    });
});
