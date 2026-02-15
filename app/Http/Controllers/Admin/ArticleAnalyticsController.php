<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleAnalyticsController extends Controller
{
    public function index()
    {
        // Only admins can see site-wide analytics
        abort_unless(auth()->user()->role === 'admin', 403);

        $totalViews = News::sum('views');
        $averageViews = News::count() > 0 ? $totalViews / News::count() : 0;
        
        $topArticles = News::orderBy('views', 'desc')
            ->take(10)
            ->get();

        $categoryStats = Category::withSum('news as total_views', 'views')
            ->get();

        $recentViews = \App\Models\ArticleView::where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('admin.analytics.index', compact(
            'totalViews',
            'averageViews',
            'topArticles',
            'categoryStats',
            'recentViews'
        ));
    }
}
