<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        // Filter by category if present in URL (?category=politics)
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        $news = $query->latest()->get();

        return view('home', [
            'news' => $news,
            'category' => $request->category
        ]);
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'breaking' => 'boolean'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'image' => $imagePath,
            'breaking' => $request->has('breaking') ? 1 : 0,
        ]);

        // Trigger Breaking News Emails
        if ($news->breaking) {
            $subscribers = \App\Models\Subscriber::where('status', 'active')->get();
            foreach ($subscribers as $sub) {
                try {
                    \Illuminate\Support\Facades\Mail::to($sub->email)->send(new \App\Mail\BreakingNewsAlert($news, $sub->email));
                } catch (\Exception $e) {
                    // Continue to next subscriber
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'News article created successfully!');
    }
}
