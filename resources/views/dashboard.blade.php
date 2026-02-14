<x-admin-layout>
    @section('header', 'Dashboard Overview')

    <!-- STATS ROW -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="p-6 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color); border-left-color: #3b82f6;">
            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-sidebar)">Total Articles</div>
            <div class="mt-2 text-3xl font-bold" style="color: var(--text-main)">{{ $totalNews }}</div>
            <div class="mt-1 text-xs text-green-600 dark:text-green-400 flex items-center">
                <span class="mr-1">↑</span> {{ $publishedCount }} Published
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
        <!-- Total Subscribers -->
        <div class="p-6 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color); border-left-color: #10b981;">
            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-sidebar)">Total Subscribers</div>
            <div class="mt-2 text-3xl font-bold" style="color: var(--text-main)">{{ $totalSubscribers }}</div>
        </div>
        @endif

        <!-- Breaking News -->
        <div class="p-6 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color); border-left-color: #ef4444;">
            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-sidebar)">Breaking News</div>
            <div class="mt-2 text-3xl font-bold" style="color: var(--text-main)">{{ $breakingCount }}</div>
            <div class="text-xs text-red-500 mt-1">Currently Active</div>
        </div>
    </div>

    <!-- CONTENT INSIGHTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="p-4 rounded-lg shadow-sm flex items-center justify-between border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div>
                <div class="text-xs uppercase font-bold" style="color: var(--text-sidebar)">Drafts</div>
                <div class="text-2xl font-bold" style="color: var(--text-main)">{{ $draftsCount }}</div>
            </div>
            <div class="p-2 rounded" style="background: var(--sidebar-hover); color: var(--text-sidebar)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>
        <div class="p-4 rounded-lg shadow-sm flex items-center justify-between border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div>
                <div class="text-xs uppercase font-bold" style="color: var(--text-sidebar)">Scheduled</div>
                <div class="text-2xl font-bold" style="color: var(--text-main)">{{ $scheduledCount }}</div>
            </div>
            <div class="p-2 rounded bg-yellow-100/20 text-yellow-600 dark:text-yellow-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex flex-wrap gap-4 mb-8">
        <a href="{{ route('news.create') }}" class="font-bold py-3 px-6 rounded shadow flex items-center gap-2 border transition-all hover:opacity-80" style="background: var(--sidebar-bg); color: var(--text-main); border-color: var(--border-color);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--text-sidebar)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Write New Article
        </a>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.subscribers') }}" class="font-bold py-3 px-6 rounded shadow flex items-center gap-2 border transition-all hover:opacity-80" style="background: var(--sidebar-bg); color: var(--text-main); border-color: var(--border-color);">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--text-sidebar)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Manage Subscribers
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- LATEST ARTICLES -->
        <div class="rounded-lg shadow-sm overflow-hidden border transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="px-6 py-4 border-b flex justify-between items-center" style="background: var(--sidebar-hover); border-color: var(--border-color)">
                <h3 class="font-bold" style="color: var(--text-main)">Latest Articles</h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-bold hover:opacity-80 transition-all uppercase tracking-widest" style="color: var(--primary)">View All</a>
            </div>
            @if($latestArticles->count() > 0)
                <table class="w-full text-left">
                    <tbody class="divide-y" style="border-color: var(--border-color)">
                        @foreach($latestArticles as $article)
                        <tr class="transition-colors hover:opacity-80" style="background: transparent; border-color: var(--border-color)">
                            <td class="px-6 py-4">
                                <span class="font-medium block" style="color: var(--text-main)">{{ Str::limit($article->title, 40) }}</span>
                                <span class="text-xs" style="color: var(--text-sidebar)">{{ $article->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$article->status" />
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs" style="color: var(--text-sidebar)">{{ $article->created_at->format('M d') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500 text-sm">
                    No articles published yet.
                </div>
            @endif
        </div>

        @if(auth()->user()->role === 'admin')
        <!-- RECENT SUBSCRIBERS -->
        <div class="rounded-lg shadow-sm overflow-hidden border transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="px-6 py-4 border-b flex justify-between items-center" style="background: var(--sidebar-hover); border-color: var(--border-color)">
                <h3 class="font-bold" style="color: var(--text-main)">New Subscribers</h3>
                <a href="{{ route('admin.subscribers') }}" class="text-xs font-bold hover:opacity-80 transition-all uppercase tracking-widest" style="color: var(--primary)">Manage</a>
            </div>
            @if($recentSubscribers->count() > 0)
                <table class="w-full text-left">
                    <tbody class="divide-y" style="border-color: var(--border-color)">
                        @foreach($recentSubscribers as $sub)
                        <tr class="transition-colors hover:opacity-80" style="background: transparent; border-color: var(--border-color)">
                            <td class="px-6 py-4">
                                <span class="block font-medium" style="color: var(--text-main)">{{ $sub->email }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-status-badge :status="$sub->status" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-6 text-center text-gray-500 text-sm">
                    No subscribers yet.
                </div>
            @endif
        </div>
        @endif
    </div>
</x-admin-layout>
