<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::get('/documentaries/{documentary}', [App\Http\Controllers\DocumentaryController::class, 'show'])->name('documentaries.show');
Route::redirect('/documentaries', '/#documentaryArea');
Route::post('/news/{news}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
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

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('admin.notifications.unread-count');
    Route::get('/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('admin.notifications.recent');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('admin.notifications.destroy');

    // Security & Audit
    Route::get('/security/login-history', [App\Http\Controllers\SecurityController::class, 'loginHistory'])->name('admin.security.login-history');
    Route::get('/security/activity-logs', [App\Http\Controllers\SecurityController::class, 'activityLogs'])->name('admin.security.activity-logs');

    // Settings
    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/clear-cache', [App\Http\Controllers\SettingController::class, 'clearCache'])->name('admin.settings.clear-cache');

    // Comment Moderation
    Route::get('/comments', [App\Http\Controllers\CommentModerationController::class, 'index'])->name('admin.comments.index');
    Route::post('/comments/{id}/approve', [App\Http\Controllers\CommentModerationController::class, 'approve'])->name('admin.comments.approve');
    Route::post('/comments/{id}/reject', [App\Http\Controllers\CommentModerationController::class, 'reject'])->name('admin.comments.reject');
    Route::delete('/comments/{id}', [App\Http\Controllers\CommentModerationController::class, 'destroy'])->name('admin.comments.destroy');
    Route::post('/comments/{id}/block', [App\Http\Controllers\CommentModerationController::class, 'blockCommenter'])->name('admin.comments.block');
    Route::post('/comments/bulk-approve', [App\Http\Controllers\CommentModerationController::class, 'bulkApprove'])->name('admin.comments.bulk-approve');
    Route::post('/comments/bulk-reject', [App\Http\Controllers\CommentModerationController::class, 'bulkReject'])->name('admin.comments.bulk-reject');

    // Documentaries
    Route::resource('admin/documentaries', App\Http\Controllers\Admin\DocumentaryManagementController::class)->except(['show'])->names([
        'index' => 'admin.documentaries.index',
        'create' => 'admin.documentaries.create',
        'store' => 'admin.documentaries.store',
        'edit' => 'admin.documentaries.edit',
        'update' => 'admin.documentaries.update',
        'destroy' => 'admin.documentaries.destroy',
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
