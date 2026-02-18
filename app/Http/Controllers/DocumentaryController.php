<?php

namespace App\Http\Controllers;

use App\Models\Documentary;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;

class DocumentaryController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->category;
        
        // 1. Fetch Traditional Documentaries
        $docQuery = Documentary::published()->with(['author', 'categoryData']);
        if ($categorySlug) {
            $docQuery->whereHas('categoryData', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }
        $traditionalDocs = $docQuery->get();

        // 2. Fetch News Articles in the Documentary Category (only if no specific sub-category filter or if filter is 'documentary')
        $newsDocs = collect();
        if (!$categorySlug || $categorySlug === 'documentary') {
            $newsDocs = News::published()
                ->whereHas('categoryData', function($q) {
                    $q->where('slug', 'documentary');
                })
                ->with(['author', 'categoryData'])
                ->get();
        }

        // 3. Merge and Sort
        $allDocumentaries = $traditionalDocs->concat($newsDocs)
            ->sortByDesc('published_at');

        // 4. Paginate the merged collection
        $perPage = 12;
        $page = $request->get('page', 1);
        $documentaries = new \Illuminate\Pagination\LengthAwarePaginator(
            $allDocumentaries->forPage($page, $perPage),
            $allDocumentaries->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $categories = Category::whereHas('news', function($q) {
                // Filter categories that actually have content
            })->orWhereExists(function ($query) {
               $query->select(\DB::raw(1))
                     ->from('documentaries')
                     ->whereRaw('documentaries.category_id = categories.id');
            })->get();

        return view('documentaries.index', compact('documentaries', 'categories'));
    }
    public function show(Documentary $documentary)
    {
        // Only show published documentaries to guests
        if (!$documentary->isPublished() && !auth()->check()) {
            abort(404);
        }

        // Increment view count
        $documentary->incrementViews();
        
        // Eager load author
        $documentary->load('author');

        // Get related documentaries (3 items)
        $relatedDocumentaries = Documentary::published()
            ->where('id', '!=', $documentary->id)
            ->latest()
            ->with('author')
            ->take(3)
            ->get();

        // Get recent documentaries for sidebar (6 items)
        $recentDocumentaries = Documentary::published()
            ->where('id', '!=', $documentary->id)
            ->latest()
            ->with('author')
            ->take(6)
            ->get();

        return view('documentaries.show', compact('documentary', 'relatedDocumentaries', 'recentDocumentaries'));
    }
}
