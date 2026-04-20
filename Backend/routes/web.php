<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VolunteerController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// ===== AUTH =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Root redirect
Route::get('/', fn() => redirect()->route('admin.dashboard'));

// ===== ADMIN CMS =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Artikel
    Route::resource('articles', ArticleController::class);
    Route::post('upload-image', [ImageUploadController::class, 'store'])->name('upload-image');
    Route::patch('articles/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])
        ->name('articles.toggle-featured');

    // Kategori
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    // Donasi
    Route::get('donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('donations/export', [DonationController::class, 'export'])->name('donations.export');
    Route::get('donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
    Route::patch('donations/{donation}/status', [DonationController::class, 'updateStatus'])->name('donations.update-status');

    // Program
    Route::resource('programs', ProgramController::class);

    // Galeri
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('gallery/order', [GalleryController::class, 'updateOrder'])->name('gallery.order');

    // Hero Slider
    Route::get('hero', [HeroSlideController::class, 'index'])->name('hero.index');
    Route::post('hero', [HeroSlideController::class, 'store'])->name('hero.store');
    Route::put('hero/{heroSlide}', [HeroSlideController::class, 'update'])->name('hero.update');
    Route::delete('hero/{heroSlide}', [HeroSlideController::class, 'destroy'])->name('hero.destroy');
    Route::patch('hero/{heroSlide}/toggle', [HeroSlideController::class, 'toggleActive'])->name('hero.toggle');
    Route::post('hero/reorder', [HeroSlideController::class, 'reorder'])->name('hero.reorder');

    // Pengaturan Website
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Relawan
    Route::get('volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::get('volunteers/export', [VolunteerController::class, 'export'])->name('volunteers.export');
    Route::get('volunteers/{volunteer}', [VolunteerController::class, 'show'])->name('volunteers.show');
    Route::patch('volunteers/{volunteer}/status', [VolunteerController::class, 'updateStatus'])->name('volunteers.update-status');
    Route::delete('volunteers/{volunteer}', [VolunteerController::class, 'destroy'])->name('volunteers.destroy');

    // Komentar
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/spam', [CommentController::class, 'markSpam'])->name('comments.spam');
    Route::patch('comments/{comment}/unspam', [CommentController::class, 'unspam'])->name('comments.unspam');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::delete('comments', [CommentController::class, 'destroySpam'])->name('comments.destroy-spam');

    // Pengguna
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
