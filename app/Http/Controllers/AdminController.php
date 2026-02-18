<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Subscriber;

class AdminController extends Controller
{
    public function index()
    {
        $isAdmin = auth()->user()->role === 'admin';
        $userId = auth()->id();

        $totalNews = News::when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->count();
        $totalSubscribers = $isAdmin ? Subscriber::count() : 0;
        $breakingCount = News::where('breaking', 1)->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->count();
        
        $publishedCount = News::where('status', 'published')->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->count();
        $pendingCount = News::where('status', 'pending')->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->count();
        $draftsCount = News::where('status', 'draft')->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->count();
        $scheduledCount = News::where('status', 'scheduled')->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->count();

        $latestArticles = News::latest()->when(!$isAdmin, fn($q) => $q->where('user_id', $userId))->take(5)->get();
        $recentSubscribers = $isAdmin ? Subscriber::latest()->take(5)->get() : collect();

        return view('dashboard', compact(
            'totalNews', 
            'totalSubscribers', 
            'breakingCount', 
            'publishedCount',
            'pendingCount',
            'draftsCount',
            'scheduledCount',
            'latestArticles', 
            'recentSubscribers'
        ));
    }

    public function subscribers(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $query = Subscriber::latest();

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

    public function toggleSubscriberStatus($id)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $subscriber = Subscriber::findOrFail($id);
        $newStatus = $subscriber->status === 'active' ? 'unsubscribed' : 'active';
        $subscriber->update(['status' => $newStatus]);

        return back()->with('success', 'Subscriber status updated to ' . ucfirst($newStatus));
    }

    public function deleteSubscriber($id)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return back()->with('success', 'Subscriber permanently deleted.');
    }

    public function news(Request $request)
    {
        $query = News::with(['categoryData', 'author'])->latest();

        // Scope to author if user is an author
        if (auth()->user()->role === 'author') {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $latestArticles = $query->paginate(15);
        
        return view('admin.news.index', compact('latestArticles'));
    }
}
