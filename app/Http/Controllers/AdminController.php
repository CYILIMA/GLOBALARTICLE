<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class AdminController extends Controller
{
    public function index()
    {
        $totalNews = News::count();
        $totalSubscribers = \App\Models\Subscriber::count();
        $breakingCount = News::where('breaking', 1)->count();
        
        // New metrics for Admin Upgrade
        $draftsCount = News::where('status', 'draft')->count();
        $scheduledCount = News::where('status', 'scheduled')->count();
        $publishedCount = News::where('status', 'published')->count();

        $latestArticles = News::latest()->take(5)->get();
        $recentSubscribers = \App\Models\Subscriber::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalNews', 
            'totalSubscribers', 
            'breakingCount', 
            'draftsCount',
            'scheduledCount',
            'publishedCount',
            'latestArticles', 
            'recentSubscribers'
        ));
    }

    public function subscribers(Request $request)
    {
        $query = \App\Models\Subscriber::latest();

        if ($request->has('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('export')) {
            $fileName = 'subscribers.csv';
            $subscribers = $query->get();

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('Email', 'Status', 'Joined Date');

            $callback = function() use($subscribers, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($subscribers as $subscriber) {
                    $row['Email']  = $subscriber->email;
                    $row['Status'] = $subscriber->status;
                    $row['Joined Date'] = $subscriber->created_at;

                    fputcsv($file, array($row['Email'], $row['Status'], $row['Joined Date']));
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $subscribers = $query->paginate(20);
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function news(Request $request)
    {
        $query = News::with('categoryData')->latest();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $latestArticles = $query->paginate(15);
        
        return view('admin.news.index', compact('latestArticles'));
    }
}
