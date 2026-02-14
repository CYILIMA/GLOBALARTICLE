<x-admin-layout>
    @section('header', 'Notifications')

    <div class="max-w-4xl">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Notification History</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage and review all your system alerts.</p>
            </div>
            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Mark All as Read
                </button>
            </form>
        </div>

        <!-- Notifications List Container -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
            @forelse($notifications as $notification)
                <div class="p-5 border-b border-gray-200/30 dark:border-gray-700/30 hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-all group relative">
                    <!-- Unread Indicator Dot -->
                    @if($notification->isUnread())
                        <div class="absolute left-2 top-1/2 -translate-y-1/2 w-1 h-10 bg-blue-600 dark:bg-blue-500 rounded-full opacity-50"></div>
                        <div class="absolute right-6 top-6 w-3 h-3 bg-blue-600 dark:bg-blue-500 rounded-full ring-4 ring-blue-100 dark:ring-blue-900/20"></div>
                    @endif

                    <div class="flex items-start gap-5">
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0 transition-transform group-hover:scale-110 shadow-sm"
                             style="background: {{ $notification->isUnread() ? 'rgba(59, 130, 246, 0.1)' : 'var(--sidebar-hover)' }}">
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
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="{{ $notification->isUnread() ? 'font-bold text-gray-900 dark:text-white' : 'font-semibold text-gray-600 dark:text-gray-300' }} text-lg tracking-tight">
                                            {{ $notification->title }}
                                        </h3>
                                        @if($notification->isUnread())
                                            <span class="px-2 py-0.5 text-[10px] bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full font-bold uppercase tracking-wider">New</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed max-w-2xl">{{ $notification->message }}</p>
                                    <div class="flex items-center gap-2 mt-3 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-3 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($notification->isUnread())
                                        <form action="{{ route('admin.notifications.read', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-colors border border-blue-200 dark:border-blue-800/50">
                                                Mark Read
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete Notification">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center flex flex-col items-center gap-4">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700/50 rounded-full flex items-center justify-center text-5xl mb-2">🔔</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">All caught up!</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">You don't have any notifications at the moment. Check back later for system updates.</p>
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
