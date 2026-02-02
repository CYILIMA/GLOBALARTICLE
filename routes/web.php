<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentModerationController;
use App\Http\Controllers\DocumentaryController;
use App\Http\Controllers\Admin\DocumentaryManagementController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');

// Admin-specific news routes (must come before wildcard)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
});

// Public news routes (wildcard must come after specific routes)
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/documentaries/{documentary}', [DocumentaryController::class, 'show'])->name('documentaries.show');
Route::redirect('/documentaries', '/#documentaryArea');
Route::post('/news/{news}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/subscribe', [SubscriberController::class, 'store'])->name('subscribe');
Route::get('/unsubscribe', [SubscriberController::class, 'unsubscribe'])->name('unsubscribe');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // News Management
    Route::get('/dashboard/news', [AdminController::class, 'news'])->name('admin.news.index');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

    // Subscriber Management
    Route::get('/subscribers', [AdminController::class, 'subscribers'])->name('admin.subscribers');
    Route::patch('/subscribers/{id}/toggle', [AdminController::class, 'toggleSubscriberStatus'])->name('admin.subscribers.toggle');
    Route::delete('/subscribers/{id}', [AdminController::class, 'deleteSubscriber'])->name('admin.subscribers.delete');

    // Category Management
    Route::resource('/categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // Media Library
    Route::post('/media/folder', [MediaController::class, 'createFolder'])->name('admin.media.folder.create');
    Route::resource('/media', MediaController::class)->names([
        'index' => 'admin.media.index',
        'store' => 'admin.media.store',
        'destroy' => 'admin.media.destroy',
    ]);
    
    // Article Image Upload (for Editor.js)
    Route::post('/admin/upload-article-image', [NewsController::class, 'uploadArticleImage'])->name('admin.upload-article-image');


    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('admin.notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('admin.notifications.recent');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');

    // Security & Audit
    Route::get('/security/login-history', [SecurityController::class, 'loginHistory'])->name('admin.security.login-history');
    Route::get('/security/activity-logs', [SecurityController::class, 'activityLogs'])->name('admin.security.activity-logs');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('admin.settings.clear-cache');

    // Comment Moderation
    Route::get('/comments', [CommentModerationController::class, 'index'])->name('admin.comments.index');
    Route::post('/comments/{id}/approve', [CommentModerationController::class, 'approve'])->name('admin.comments.approve');
    Route::post('/comments/{id}/reject', [CommentModerationController::class, 'reject'])->name('admin.comments.reject');
    Route::delete('/comments/{id}', [CommentModerationController::class, 'destroy'])->name('admin.comments.destroy');
    Route::post('/comments/{id}/block', [CommentModerationController::class, 'blockCommenter'])->name('admin.comments.block');
    Route::post('/comments/bulk-approve', [CommentModerationController::class, 'bulkApprove'])->name('admin.comments.bulk-approve');
    Route::post('/comments/bulk-reject', [CommentModerationController::class, 'bulkReject'])->name('admin.comments.bulk-reject');

    // Documentaries
    Route::resource('admin/documentaries', DocumentaryManagementController::class)->except(['show'])->names([
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
