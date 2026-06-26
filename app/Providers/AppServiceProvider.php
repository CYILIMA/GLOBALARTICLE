<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    private function adsFor(string $position): array
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('advertisements')) {
            return [];
        }
        return \App\Models\Advertisement::getAllActiveForPosition($position)
            ->map->toDisplayArray()
            ->values()
            ->all();
    }

    public function boot(): void
    {
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
                    $categories = \App\Models\Category::where('slug', '!=', 'documentary')->get();
                    $view->with('globalCategories', $categories);
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $settings = \App\Models\Setting::getAll();
                    $view->with('globalSettings', $settings);
                }

                $view->with('globalHeaderAds', $this->adsFor('header_leaderboard'));
                $view->with('globalFooterAds',  $this->adsFor('footer_banner'));
            } catch (\Exception $e) {}
        });

        \Illuminate\Support\Facades\View::composer('partials.sidebar', function ($view) {
            try {
                $view->with('globalSidebarAds', $this->adsFor('sidebar_rectangle'));
            } catch (\Exception $e) {}
        });

        \Illuminate\Support\Facades\View::composer('news.show', function ($view) {
            try {
                $view->with('globalInArticleAds', $this->adsFor('in_article'));
            } catch (\Exception $e) {}
        });

        \Illuminate\Support\Facades\View::composer('home', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('news')) {
                    $sidebarLatest = \App\Models\News::where('status', 'published')->latest()->take(5)->get();
                    $view->with('sidebarLatest', $sidebarLatest);
                }
            } catch (\Exception $e) {}
        });
    }
}
