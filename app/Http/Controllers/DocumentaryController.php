<?php

namespace App\Http\Controllers;

use App\Models\Documentary;
use Illuminate\Http\Request;

class DocumentaryController extends Controller
{
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
