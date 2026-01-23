<x-admin-layout>
    @section('header', 'Comment Moderation')

    <div class="max-w-6xl">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4 flex gap-4">
            <form method="GET" class="flex gap-4 flex-1">
                <select name="status" onchange="this.form.submit()" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg text-sm dark:text-white">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="flagged" value="1" {{ request('flagged') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Flagged Only</span>
                </label>
            </form>
        </div>

        <!-- Comments List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            @forelse($comments as $comment)
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 {{ $comment->flagged ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ $comment->author_name }}</h4>
                                <span class="text-xs text-gray-500">{{ $comment->author_email }}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                    {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $comment->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                    {{ ucfirst($comment->status) }}
                                </span>
                                @if($comment->flagged)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        🚩 Flagged
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                On: <a href="{{ route('news.show', $comment->news) }}" class="text-blue-600 hover:underline">{{ $comment->news->title }}</a>
                            </p>
                            <p class="text-gray-900 dark:text-gray-100">{{ $comment->content }}</p>
                            @if($comment->flag_reason)
                                <p class="text-xs text-red-600 dark:text-red-400 mt-2">⚠️ {{ $comment->flag_reason }}</p>
                            @endif
                            <div class="text-xs text-gray-500 mt-2">
                                IP: {{ $comment->ip_address }} | {{ $comment->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            @if($comment->status !== 'approved')
                                <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">Approve</button>
                                </form>
                            @endif
                            @if($comment->status !== 'rejected')
                                <form action="{{ route('admin.comments.reject', $comment) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">Reject</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.comments.block', $comment) }}" method="POST" onsubmit="return confirm('Block this commenter?')">
                                @csrf
                                <button type="submit" class="text-xs bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded transition">Block</button>
                            </form>
                            <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs bg-gray-800 hover:bg-gray-900 text-white px-3 py-1 rounded transition">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                    No comments found
                </div>
            @endforelse

            @if($comments->hasPages())
                <div class="p-4">
                    {{ $comments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
