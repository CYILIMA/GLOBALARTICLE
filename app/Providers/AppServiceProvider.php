<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use View Composer to share data only when 'layouts.main' is rendered
        // This prevents database queries on admin routes or API routes
        \Illuminate\Support\Facades\View::composer('layouts.main', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('news')) {
                    $breakingNews = \App\Models\News::where('breaking', true)
                        ->where('status', 'published')
                        ->where(function($q) {
                            $q->whereNull('breaking_expires_at')
                              ->orWhere('breaking_expires_at', '>', now());
                        })
                        ->latest()
                        ->take(5)
                        ->get();
                    $view->with('globalBreakingNews', $breakingNews);
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                    $categories = \App\Models\Category::all();
                    $view->with('globalCategories', $categories);
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $settings = \App\Models\Setting::getAll();
                    $view->with('globalSettings', $settings);
                }
            } catch (\Exception $e) {
                // Ignore DB errors during setup
            }
        });

        // Share sidebarLatest with home view
        \Illuminate\Support\Facades\View::composer('home', function ($view) {
             try {
                if (\Illuminate\Support\Facades\Schema::hasTable('news')) {
                    $sidebarLatest = \App\Models\News::where('status', 'published')->latest()->take(5)->get();
                    $view->with('sidebarLatest', $sidebarLatest);
                }
            } catch (\Exception $e) {
                // Ignore during setup
            }
        });
    }
}
