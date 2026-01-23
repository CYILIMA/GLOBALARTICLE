<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');
Route::get('/unsubscribe', [SubscriberController::class, 'unsubscribe'])->name('unsubscribe');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // News Management
    // News Management
    Route::get('/dashboard/news', [AdminController::class, 'news'])->name('admin.news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

    // Subscriber Management
    Route::get('/subscribers', [AdminController::class, 'subscribers'])->name('admin.subscribers');

    // Category Management
    Route::resource('/categories', \App\Http\Controllers\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // Media Library
    Route::post('/media/folder', [App\Http\Controllers\MediaController::class, 'createFolder'])->name('admin.media.folder.create');
    Route::resource('/media', App\Http\Controllers\MediaController::class)->names([
        'index' => 'admin.media.index',
        'store' => 'admin.media.store',
        'destroy' => 'admin.media.destroy',
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
