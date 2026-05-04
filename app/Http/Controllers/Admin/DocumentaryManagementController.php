<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentary;
use App\Models\ActivityLog;
use App\Support\AllowedImageUploads;
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
            'thumbnail' => AllowedImageUploads::rules(maxKilobytes: 2048),
            'video_url' => 'nullable|url',
            'status' => 'required|in:draft,published,pending',
        ]);

        // Enforcement of publishing rights
        $finalStatus = $request->status;
        if ($finalStatus === 'published' && !auth()->user()->can('publish', Documentary::class)) {
            $finalStatus = 'pending';
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
            'status' => $finalStatus,
            'published_at' => $finalStatus == 'published' ? now() : null,
        ]);

        // Notify Admins if Pending
        if ($documentary->status === 'pending') {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Services\NotificationService::documentaryPendingReview($admin->id, $documentary);
            }
        }

        ActivityLog::log('created', 'Documentary', $documentary->id, "Created documentary: {$documentary->title}");

        $message = $documentary->status === 'pending' 
            ? 'Documentary submitted successfully and is awaiting administrator approval.' 
            : 'Documentary created successfully!';

        return redirect()->route('admin.documentaries.index')->with('success', $message);
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
            'thumbnail' => AllowedImageUploads::rules(maxKilobytes: 2048),
            'video_url' => 'nullable|url',
            'status' => 'required|in:draft,published,pending',
        ]);

        // Enforcement of publishing rights
        $finalStatus = $request->status;
        if ($finalStatus === 'published' && !auth()->user()->can('publish', Documentary::class)) {
            $finalStatus = 'pending';
        }

        $thumbnailPath = $documentary->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('documentaries', 'public');
        }

        $oldStatus = $documentary->status;

        $documentary->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'video_url' => $request->video_url,
            'status' => $finalStatus,
            'published_at' => $finalStatus === 'published'
                ? ($documentary->status === 'published' ? $documentary->published_at : now())
                : null,
        ]);

        // Notify Admins if newly Pending
        if ($oldStatus !== 'pending' && $documentary->status === 'pending') {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Services\NotificationService::documentaryPendingReview($admin->id, $documentary);
            }
        }

        // Notify Author if Admin publishes their pending entry
        if ($oldStatus === 'pending' && $documentary->status === 'published') {
            \App\Services\NotificationService::documentaryPublished($documentary->user_id, $documentary);
        }

        ActivityLog::log('updated', 'Documentary', $documentary->id, "Updated documentary: {$documentary->title}");

        $message = $documentary->status === 'pending' && $oldStatus !== 'pending'
            ? 'Documentary updated and submitted for review.' 
            : 'Documentary updated successfully!';

        return redirect()->route('admin.documentaries.index')->with('success', $message);
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
