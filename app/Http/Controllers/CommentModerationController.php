<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with('news')->latest();

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Show only flagged
        if ($request->has('flagged') && $request->flagged) {
            $query->where('flagged', true);
        }

        $comments = $query->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approve();

        ActivityLog::log('approved', 'Comment', $comment->id, "Approved comment from {$comment->author_name}");

        return back()->with('success', 'Comment approved!');
    }

    public function reject($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->reject();

        ActivityLog::log('rejected', 'Comment', $comment->id, "Rejected comment from {$comment->author_name}");

        return back()->with('success', 'Comment rejected!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        ActivityLog::log('deleted', 'Comment', $id, "Deleted comment from {$comment->author_name}");

        return back()->with('success', 'Comment deleted!');
    }

    public function blockCommenter(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        DB::table('blocked_commenters')->insert([
            'email' => $comment->author_email,
            'ip_address' => $comment->ip_address,
            'reason' => $request->reason ?? 'Blocked by admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $comment->reject();

        ActivityLog::log('blocked', 'Commenter', null, "Blocked {$comment->author_email}");

        return back()->with('success', 'Commenter blocked!');
    }

    public function bulkApprove(Request $request)
    {
        Comment::whereIn('id', $request->comment_ids)->update(['status' => 'approved', 'flagged' => false]);

        return back()->with('success', count($request->comment_ids) . ' comments approved!');
    }

    public function bulkReject(Request $request)
    {
        Comment::whereIn('id', $request->comment_ids)->update(['status' => 'rejected']);

        return back()->with('success', count($request->comment_ids) . ' comments rejected!');
    }
}
