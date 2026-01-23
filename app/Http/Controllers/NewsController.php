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

        $news = $query->where('status', 'published')->latest()->get();

        return view('home', [
            'news' => $news,
            'category' => $request->category
        ]);
    }

    public function show(News $news)
    {
        if ($news->status !== 'published' && !auth()->check()) {
            abort(404);
        }

        $relatedNews = News::where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id', // Validating real category
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'breaking' => 'boolean',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'breaking_expires_at' => 'nullable|date',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $category = \App\Models\Category::find($request->category_id);

        // Default expiration to 24h if breaking is checked but no date provided
        $expiresAt = $request->breaking_expires_at;
        if ($request->has('breaking') && !$expiresAt) {
            $expiresAt = now()->addHours(24);
        }

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'category' => $category->name, // Keeping legacy string for backward compatibility
            'image' => $imagePath,
            'breaking' => $request->has('breaking') ? 1 : 0,
            'breaking_expires_at' => $request->has('breaking') ? $expiresAt : null,
            'status' => $request->status,
            'published_at' => $request->published_at ?? ($request->status == 'published' ? now() : null),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        // Trigger Breaking News Emails
        if ($news->breaking && $news->status == 'published') {
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

    public function edit(News $news)
    {
        $categories = \App\Models\Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'breaking' => 'boolean',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'breaking_expires_at' => 'nullable|date',
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            // Delete old image? For now, we keep it to avoid broken links if used elsewhere, 
            // or we could use Storage::delete($news->image) if we want to be clean.
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $category = \App\Models\Category::find($request->category_id);

        $expiresAt = $request->breaking_expires_at;
        if ($request->has('breaking') && !$expiresAt && !$news->breaking_expires_at) {
             // If turning on breaking and no date set, default to 24h from now
             $expiresAt = now()->addHours(24);
        } elseif ($request->has('breaking') && !$expiresAt && $news->breaking_expires_at) {
             // Keep existing if invalid
             $expiresAt = $news->breaking_expires_at;
        }

        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'category' => $category->name, // Sync legacy column
            'image' => $imagePath,
            'breaking' => $request->has('breaking') ? 1 : 0,
            'breaking_expires_at' => $request->has('breaking') ? $expiresAt : null,
            'status' => $request->status,
            'published_at' => $request->published_at ?? ($request->status == 'published' && !$news->published_at ? now() : $news->published_at),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully!');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Article deleted successfully.');
    }
}
