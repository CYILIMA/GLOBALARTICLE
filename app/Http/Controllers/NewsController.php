<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['categoryData', 'author'])->where('status', 'published')->latest();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $news = $query->paginate(12);

        // Get latest news for sidebar
        $sidebarLatest = News::where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // Get documentaries
        $documentaries = \App\Models\Documentary::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        $category = $request->category ?? null;

        return view('home', compact('news', 'sidebarLatest', 'documentaries', 'category'));
    }

    public function show(News $news)
    {
        // Increment views
        $news->increment('views');

        $relatedNews = News::where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // Get latest news for sidebar
        $sidebarLatest = News::where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        return view('news.show', compact('news', 'relatedNews', 'sidebarLatest'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $authors = \App\Models\User::whereIn('role', ['admin', 'editor', 'author'])->get();
        return view('admin.news.create', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string', // Optional for v2 (blocks)
            'content_blocks' => 'nullable|json', // New block-based content
            'content_version' => 'nullable|in:v1,v2',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
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
            'content' => $request->content ?? '', // Fallback for v2 blocks
            'content_blocks' => json_decode($request->content_blocks, true),
            'content_version' => $request->content_version ?? 'v1',
            'category_id' => $request->category_id,
            'category' => $category->name, // Keeping legacy string for backward compatibility
            'user_id' => $request->user_id ?? auth()->id(), // Set requested user or current user
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

        $message = auth()->user()->role === 'author' 
            ? 'Article submitted successfully and is awaiting administrator approval.' 
            : 'News article created successfully!';

        return redirect()->route('admin.news.index')->with('success', $message);
    }

    public function edit(News $news)
    {
        $this->authorize('update', $news);
        $categories = \App\Models\Category::all();
        $authors = \App\Models\User::whereIn('role', ['admin', 'editor', 'author'])->get();
        return view('admin.news.edit', compact('news', 'categories', 'authors'));
    }

    public function update(Request $request, News $news)
    {
        $this->authorize('update', $news);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'breaking' => 'boolean',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'breaking_expires_at' => 'nullable|date',
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image) {
                \Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $category = \App\Models\Category::find($request->category_id);

        // Default expiration to 24h if breaking is checked but no date provided
        $expiresAt = $request->breaking_expires_at;
        if ($request->has('breaking') && !$expiresAt) {
            $expiresAt = now()->addHours(24);
        }

        $news->update([
            'title' => $request->title,
            'content' => $request->content ?? '', // Fallback for v2 blocks
            'content_blocks' => json_decode($request->content_blocks, true),
            'content_version' => $request->content_version ?? $news->content_version,
            'category_id' => $request->category_id,
            'category' => $category->name,
            'user_id' => $request->user_id ?? ($news->user_id ?? auth()->id()), // Maintain, change, or set author
            'image' => $imagePath,
            'breaking' => $request->has('breaking') ? 1 : 0,
            'breaking_expires_at' => $request->has('breaking') ? $expiresAt : null,
            'status' => $request->status,
            'published_at' => $request->published_at ?? ($request->status == 'published' && !$news->published_at ? now() : $news->published_at),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        $message = auth()->user()->role === 'author' && $news->status !== 'published'
            ? 'Article updated and remains in review/draft status.' 
            : 'News article updated successfully!';

        return redirect()->route('admin.news.index')->with('success', $message);
    }

    public function destroy(News $news)
    {
        $this->authorize('delete', $news);

        if ($news->image) {
            \Storage::disk('public')->delete($news->image);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully!');
    }
    
    /**
     * Upload article image for Editor.js
     */
    public function uploadArticleImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480'
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('article_images', 'public');
            
            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => asset('storage/' . $path)
                ]
            ]);
        }
        
        return response()->json([
            'success' => 0,
            'message' => 'No image uploaded'
        ], 400);
    }
}
