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
        $query = Documentary::latest('created_at');
        
        // Filter by author if user is an author
        if (auth()->user()->role === 'author') {
            $query->where('user_id', auth()->id());
        }

        $documentaries = $query->paginate(20);
        return view('admin.documentaries.index', compact('documentaries'));
    }

    public function create()
    {
        $this->authorize('create', Documentary::class);
        $categories = \App\Models\Category::all();
        return view('admin.documentaries.create', compact('categories'));
    }

    public function show(Documentary $documentary)
    {
        // Redirect to edit page for admin
        return redirect()->route('admin.documentaries.edit', $documentary);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Documentary::class);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        // Enforcement of publishing rights
        if ($request->status === 'published' && !auth()->user()->can('publish', Documentary::class)) {
            return back()->withInput()->with('error', 'You do not have permission to publish. Your entry has been saved as a draft.');
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('documentaries', 'public');
        }

        $documentary = Documentary::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
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
        $this->authorize('update', $documentary);
        $categories = \App\Models\Category::all();
        return view('admin.documentaries.edit', compact('documentary', 'categories'));
    }

    public function update(Request $request, Documentary $documentary)
    {
        $this->authorize('update', $documentary);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
            'status' => 'required|in:draft,published',
        ]);

        // Enforcement of publishing rights
        if ($request->status === 'published' && !auth()->user()->can('publish', Documentary::class)) {
            $request->merge(['status' => 'draft']); // Force draft if not admin/editor
        }

        $thumbnailPath = $documentary->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('documentaries', 'public');
        }

        $documentary->update([
            'category_id' => $request->category_id,
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
        $this->authorize('delete', $documentary);
        $documentary->delete();

        ActivityLog::log('deleted', 'Documentary', $documentary->id, "Deleted documentary: {$documentary->title}");

        return redirect()->route('admin.documentaries.index')
            ->with('success', 'Documentary deleted successfully!');
    }
}
