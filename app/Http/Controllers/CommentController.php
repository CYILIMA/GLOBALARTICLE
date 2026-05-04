<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Request $request, News $news)
    {
        $request->validate([
            'author_name' => 'required|string|max:100',
            'author_email' => 'required|email|max:255',
            'content' => 'required|string|max:1000',
        ]);

        // Check if commenter is blocked
        $isBlocked = DB::table('blocked_commenters')
            ->where('email', $request->author_email)
            ->orWhere('ip_address', $request->ip())
            ->exists();

        if ($isBlocked) {
            return back()->with('error', 'You are not allowed to comment.');
        }

        // Check content against filters
        $filters = DB::table('comment_filters')->get();
        $status = 'pending';
        $flagged = false;
        $flagReason = null;

        foreach ($filters as $filter) {
            if (stripos($request->content, $filter->keyword) !== false) {
                if ($filter->action === 'reject') {
                    return back()->with('error', 'Your comment contains prohibited content.');
                } elseif ($filter->action === 'flag') {
                    $flagged = true;
                    $flagReason = "Contains keyword: {$filter->keyword}";
                }
            }
        }

        $comment = Comment::create([
            'news_id' => $news->id,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'content' => $request->content,
            'status' => $status,
            'ip_address' => $request->ip(),
            'flagged' => $flagged,
            'flag_reason' => $flagReason,
        ]);

        // Notify Admins for moderation
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Services\NotificationService::newComment($admin->id, $comment, $news);
        }

        // Notify Author if the commenter is not the author themselves and the author was not already notified as an admin
        if ($news->user_id && $news->user_id !== auth()->id() && !$admins->contains('id', $news->user_id)) {
            \App\Services\NotificationService::newComment($news->user_id, $comment, $news);
        }

        // Notify the commenter if they are logged in
        if (auth()->check()) {
            \App\Services\NotificationService::commentSubmitted(auth()->id(), $comment, $news);
        }

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
}
