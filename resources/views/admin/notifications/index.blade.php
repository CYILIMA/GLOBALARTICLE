<x-admin-layout>
    @section('header', 'Notifications')

    <div class="max-w-4xl">
        <!-- Header Actions -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Notifications</h2>
            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Mark All as Read
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 {{ $notification->isUnread() ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="text-3xl">
                            @if($notification->type === 'comment')
                                💬
                            @elseif($notification->type === 'article_published')
                                📰
                            @elseif($notification->type === 'system_warning')
                                ⚠️
                            @elseif($notification->type === 'email_failed')
                                📧
                            @else
                                🔔
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $notification->title }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                                    <span class="text-xs text-gray-500 mt-2 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex gap-2">
                                    @if($notification->isUnread())
                                        <form action="{{ route('admin.notifications.read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:underline">Mark read</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">🔔</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No notifications yet</h3>
                    <p class="text-gray-600 dark:text-gray-400">You're all caught up!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
