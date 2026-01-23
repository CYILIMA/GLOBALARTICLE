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

        // Get related documentaries
        $relatedDocumentaries = Documentary::published()
            ->where('id', '!=', $documentary->id)
            ->latest()
            ->take(3)
            ->get();

        return view('documentaries.show', compact('documentary', 'relatedDocumentaries'));
    }
}
