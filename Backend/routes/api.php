<?php

use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\DonationApiController;
use App\Http\Controllers\Api\GalleryApiController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\VolunteerApiController;
use Illuminate\Support\Facades\Route;

// ===== HOMEPAGE DATA =====
Route::get('/v1/homepage', HomepageController::class);

// ===== DONATION API (Midtrans) =====
Route::prefix('v1')->group(function () {
    Route::post('/donations', [DonationApiController::class, 'create']);
    Route::post('/donations/callback', [DonationApiController::class, 'callback']);
    Route::get('/donations/{orderId}/status', [DonationApiController::class, 'status']);

    // Volunteer registration
    Route::post('/volunteers', [VolunteerApiController::class, 'store']);

    // Contact
    Route::post('/contact', [ContactApiController::class, 'store']);

    // Comments
    Route::get('/articles/{article}/comments', [CommentApiController::class, 'index']);
    Route::post('/articles/{article}/comments', [CommentApiController::class, 'store']);

    // Campaigns (public)
    Route::get('/config', [CampaignController::class, 'config']);
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::get('/campaigns/{program:slug}', [CampaignController::class, 'show']);

    // Gallery (public)
    Route::get('/gallery', [GalleryApiController::class, 'index']);

    // Articles (public)
    Route::get('/articles', [ArticleApiController::class, 'index']);
    Route::get('/articles/{article:slug}', [ArticleApiController::class, 'show']);
    Route::get('/tags/{tag:slug}/articles', [ArticleApiController::class, 'byTag']);
});
