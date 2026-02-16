<?php

namespace App\Http\Controllers;

use App\Models\Documentary;
use App\Models\Category;
use Illuminate\Http\Request;

class DocumentaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Documentary::published()->with(['author', 'categoryData'])->latest();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('categoryData', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $documentaries = $query->paginate(12);
        $categories = Category::whereHas('news', function($q) {
                // You might want to filter categories that have documentaries
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
