<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Media;
use App\Models\MediaFolder;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Media::query();
            
            if ($request->has('folder_id')) {
                if ($request->folder_id == 'null') {
                    $query->whereNull('folder_id');
                } else {
                    $query->where('folder_id', $request->folder_id);
                }
            }
            
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            return response()->json([
                'media' => $query->latest()->get()->map(function($item) {
                    $item->url = $item->url; // Append accessor
                    return $item;
                }),
                'folders' => MediaFolder::where('parent_id', $request->folder_id == 'null' ? null : $request->folder_id)->get()
            ]);
        }

        return view('admin.media.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:1048576', // 1GB limit for videos? Or 10MB? user said "images, videos", let's say 50MB for now: 51200
            'folder_id' => 'nullable|exists:media_folders,id'
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $media = Media::create([
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'folder_id' => $request->folder_id,
        ]);

        return response()->json($media);
    }

    public function destroy(string $id)
    {
        $media = Media::find($id);
        if (!$media) return response()->json(['error' => 'Not found'], 404);

        // Delete physical file
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_path);
        }
        
        $media->delete();

        return response()->json(['success' => true]);
    }

    public function createFolder(Request $request)
    {
        $request->validate(['name' => 'required']);
        
        $folder = MediaFolder::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);
        
        return response()->json($folder);
    }
}
