<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use App\Support\AllowedImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // SEO: old ?category= query links now resolve to the clean /{category} URL
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)
                ->orWhere('name', $request->category)
                ->first();

            if ($category) {
                return redirect()->route('category.show', $category, 301);
            }
        }

        $query = News::with(['categoryData', 'author'])
            ->where('status', 'published')
            ->whereHas('categoryData', function($q) {
                $q->where('slug', '!=', 'documentary');
            })
            ->latest();

        $news = $query->paginate(12);

        // Get latest news for sidebar
        $sidebarLatest = News::where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // Get unified documentaries for home page
        $traditionalDocs = \App\Models\Documentary::published()
            ->latest()
            ->with('author')
            ->take(6)
            ->get();

        $newsDocs = \App\Models\News::published()
            ->whereHas('categoryData', function($q) {
                $q->where('slug', 'documentary');
            })
            ->latest()
            ->with('author')
            ->take(6)
            ->get();

        $documentaries = $traditionalDocs->concat($newsDocs)
            ->sortByDesc('published_at')
            ->take(6);

        return view('home', compact('news', 'sidebarLatest', 'documentaries'));
    }

    /**
     * Clean-URL category page (e.g. /technology) — first-class landing page
     * with its own title, description, paginated articles, and SEO metadata.
     */
    public function category(Category $category)
    {
        abort_if($category->slug === 'documentary', 404);

        $news = News::with(['categoryData', 'author'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        $sidebarLatest = News::where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        return view('category', compact('category', 'news', 'sidebarLatest'));
    }

    public function show(News $news)
    {
        // Log the view in article_views table (real tracking)
        \App\Models\ArticleView::create([
            'news_id' => $news->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Still increment the main counter for simple display and sorting
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

    public function showAuthor(User $author)
    {
        $articles = News::published()
            ->where('user_id', $author->id)
            ->latest()
            ->paginate(12);

        $sidebarLatest = News::published()->latest()->take(5)->get();

        return view('authors.show', compact('author', 'articles', 'sidebarLatest'));
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
            'image' => AllowedImageUploads::rules(),
            'image_source' => 'nullable|string|max:255',
            'breaking' => 'boolean',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'breaking_duration'      => 'nullable|integer|min:1|max:8760',
            'breaking_duration_unit' => 'nullable|in:hours,days',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $category = \App\Models\Category::find($request->category_id);

        if ($request->has('breaking')) {
            if ($request->filled('breaking_duration')) {
                $duration  = (int) $request->breaking_duration;
                $expiresAt = $request->breaking_duration_unit === 'days'
                    ? now()->addDays($duration)
                    : now()->addHours($duration);
            } else {
                $expiresAt = now()->addHours(24);
            }
        } else {
            $expiresAt = null;
        }


        $news = News::create([
            'title' => $request->title,
            'slug'  => News::generateSlug($request->title),
            'content' => $request->content ?? '', // Fallback for v2 blocks
            'content_blocks' => json_decode($request->content_blocks, true),
            'content_version' => $request->content_version ?? 'v1',
            'category_id' => $request->category_id,
            'category' => $category->name, // Keeping legacy string for backward compatibility
            'user_id' => $request->user_id ?? auth()->id(), // Set requested user or current user
            'image' => $imagePath,
            'image_source' => $request->image_source,
            'breaking' => $request->has('breaking') ? 1 : 0,
            'breaking_expires_at' => $request->has('breaking') ? $expiresAt : null,
            'status' => $request->status,
            'published_at' => $request->published_at ?? ($request->status == 'published' ? now() : null),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        // Enforcement of publishing rights for Authors
        if ($news->status === 'published' && auth()->user()->role === 'author') {
            $news->update(['status' => 'pending', 'published_at' => null]);

            // Notify ALL Admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Services\NotificationService::articlePendingReview($admin->id, $news);
            }
        }

        // Trigger Breaking News Emails
        if ($news->status === 'published' && $news->breaking) {
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
            'image' => AllowedImageUploads::rules(),
            'image_source' => 'nullable|string|max:255',
            'breaking' => 'boolean',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'breaking_duration'      => 'nullable|integer|min:1|max:8760',
            'breaking_duration_unit' => 'nullable|in:hours,days',
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

        if ($request->has('breaking')) {
            if ($request->filled('breaking_duration')) {
                $duration  = (int) $request->breaking_duration;
                $expiresAt = $request->breaking_duration_unit === 'days'
                    ? now()->addDays($duration)
                    : now()->addHours($duration);
            } else {
                // Keep existing future expiry; otherwise default to 24 hours from now
                $expiresAt = ($news->breaking_expires_at && $news->breaking_expires_at->isFuture())
                    ? $news->breaking_expires_at
                    : now()->addHours(24);
            }
        } else {
            $expiresAt = null;
        }

        $oldStatus = $news->status;

        $news->update([
            'title' => $request->title,
            'slug'  => $news->slug ?: News::generateSlug($request->title, $news->id),
            'content' => $request->content ?? '', // Fallback for v2 blocks
            'content_blocks' => json_decode($request->content_blocks, true),
            'content_version' => $request->content_version ?? $news->content_version,
            'category_id' => $request->category_id,
            'category' => $category->name,
            'user_id' => $request->user_id ?? ($news->user_id ?? auth()->id()), // Maintain, change, or set author
            'image' => $imagePath,
            'image_source' => $request->image_source,
            'breaking' => $request->has('breaking') ? 1 : 0,
            'breaking_expires_at' => $request->has('breaking') ? $expiresAt : null,
            'status' => $request->status,
            'published_at' => $request->published_at ?? ($request->status == 'published' && !$news->published_at ? now() : $news->published_at),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        // Enforcement for authors
        if ($news->status === 'published' && auth()->user()->role === 'author') {
            $news->update(['status' => 'pending', 'published_at' => null]);

            // Notify Admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Services\NotificationService::articlePendingReview($admin->id, $news);
            }
        }

        // Notify Author if Admin publishes their pending article
        if ($oldStatus === 'pending' && $news->status === 'published') {
            \App\Services\NotificationService::articlePublished($news->user_id, $news);
        }

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
            'image' => AllowedImageUploads::rules(required: true)
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
