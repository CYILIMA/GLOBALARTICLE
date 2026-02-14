<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentary;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentaryManagementController extends Controller
{
    public function index()
    {
        $documentaries = Documentary::latest('created_at')->paginate(20);
        return view('admin.documentaries.index', compact('documentaries'));
    }

    public function create()
    {
        return view('admin.documentaries.create');
    }

    public function show(Documentary $documentary)
    {
        // Redirect to edit page for admin
        return redirect()->route('admin.documentaries.edit', $documentary);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('documentaries', 'public');
        }

        $documentary = Documentary::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'video_url' => $request->video_url,
            'status' => $request->status,
            'published_at' => $request->status == 'published' ? now() : null,
        ]);

        ActivityLog::log('created', 'Documentary', $documentary->id, "Created documentary: {$documentary->title}");

        return redirect()->route('admin.documentaries.index')
            ->with('success', 'Documentary created successfully!');
    }

    public function edit(Documentary $documentary)
    {
        return view('admin.documentaries.edit', compact('documentary'));
    }

    public function update(Request $request, Documentary $documentary)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        $thumbnailPath = $documentary->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('documentaries', 'public');
        }

        $documentary->update([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'video_url' => $request->video_url,
            'status' => $request->status,
            'published_at' => $request->status == 'published' && !$documentary->published_at ? now() : $documentary->published_at,
        ]);

        ActivityLog::log('updated', 'Documentary', $documentary->id, "Updated documentary: {$documentary->title}");

        return redirect()->route('admin.documentaries.index')
            ->with('success', 'Documentary updated successfully!');
    }

    public function destroy(Documentary $documentary)
    {
        $documentary->delete();

        ActivityLog::log('deleted', 'Documentary', $documentary->id, "Deleted documentary: {$documentary->title}");

        return redirect()->route('admin.documentaries.index')
            ->with('success', 'Documentary deleted successfully!');
    }
}
