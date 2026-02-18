<x-admin-layout>
    @section('header', 'Activity History')

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-slate-100 tracking-tight">Notification History</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1.5 font-medium">Manage and review your recent system activity.</p>
            </div>
            
            <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" 
                        {{ $unreadCount === 0 ? 'disabled' : '' }}
                        class="w-full sm:w-auto px-6 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-100 text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 group {{ $unreadCount === 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <svg class="w-3.5 h-3.5 text-indigo-500 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Mark all as read
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
                @php $link = $notification->data['link'] ?? null; @endphp
                <div class="relative group">
                    <div class="bg-white dark:bg-slate-900/50 rounded-xl border transition-all duration-300 {{ $notification->isUnread() ? 'border-indigo-100 dark:border-indigo-500/30' : 'border-gray-50 dark:border-slate-800/10' }} hover:shadow-sm">
                        <div class="flex items-center p-4 gap-4">
                            <!-- Icon Container -->
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 transition-transform group-hover:scale-105 border {{ $notification->isUnread() ? 'bg-indigo-100/50 dark:bg-indigo-500/20 border-indigo-100 dark:border-indigo-500/20 text-indigo-600 dark:text-indigo-400' : 'bg-gray-50 dark:bg-slate-800/50 border-gray-100 dark:border-slate-800 text-gray-400 dark:text-slate-500' }}">
                                @if($notification->type === 'comment')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                @elseif($notification->type === 'article_published')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($notification->type === 'article_pending')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($notification->type === 'system_warning')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <div class="flex justify-between items-start gap-4 mb-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-[14px] font-bold tracking-tight break-all border-b border-transparent group-hover:border-current transition-colors">
                                            @if($link)
                                                <a href="{{ $link }}" class="{{ $notification->isUnread() ? 'text-gray-900 dark:text-slate-100' : 'text-gray-500 dark:text-slate-400' }}">
                                                    {{ $notification->title }}
                                                </a>
                                            @else
                                                <span class="{{ $notification->isUnread() ? 'text-gray-900 dark:text-slate-100' : 'text-gray-500 dark:text-slate-400' }}">
                                                    {{ $notification->title }}
                                                </span>
                                            @endif
                                        </h3>
                                        @if($notification->isUnread())
                                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)] shrink-0"></span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 whitespace-nowrap tabular-nums mt-0.5 shrink-0">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-[12px] {{ $notification->isUnread() ? 'text-gray-600 dark:text-slate-300 font-medium' : 'text-gray-400 dark:text-slate-500' }} break-all whitespace-normal">
                                    {{ $notification->message }}
                                </p>
                            </div>

                            <!-- Individual Actions -->
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($notification->isUnread())
                                    <form action="{{ route('admin.notifications.read', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors" title="Mark as read">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center rounded-[2rem] bg-gray-50/50 dark:bg-slate-900/30 border-2 border-dashed border-gray-100 dark:border-slate-800">
                    <div class="w-20 h-20 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-6 shadow-sm">✨</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-slate-100 tracking-tight">System is clear</h3>
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2 font-medium">You don't have any notifications to show right now.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-10">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
