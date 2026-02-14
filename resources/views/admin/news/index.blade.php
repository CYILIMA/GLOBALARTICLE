<x-admin-layout>
    @section('header', 'Manage Articles')

    <div class="rounded-lg shadow-sm overflow-hidden border transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
        
        <!-- Toolbar -->
        <div class="p-4 border-b flex flex-col sm:flex-row justify-between items-center gap-4" style="background: var(--sidebar-hover); border-color: var(--border-color)">
            <div class="flex gap-2">
                <a href="{{ route('dashboard', ['status' => 'all']) }}" class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'all' || !request('status') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">All</a>
                <a href="{{ route('dashboard', ['status' => 'published']) }}" class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Published</a>
                <a href="{{ route('dashboard', ['status' => 'draft']) }}" class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'draft' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Drafts</a>
                <a href="{{ route('dashboard', ['status' => 'scheduled']) }}" class="px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'scheduled' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Scheduled</a>
            </div>

            <a href="{{ route('news.create') }}" class="text-sm font-bold py-2 px-4 rounded transition flex items-center gap-2 border" style="background: var(--button-bg); color: var(--button-text); border-color: var(--button-border);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Article
            </a>
        </div>

        @if($latestArticles->count() > 0)
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Author</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Published</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color)">
                @foreach($latestArticles as $article)
                <tr class="transition-colors hover:opacity-80" style="background: transparent; border-color: var(--border-color)">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" class="w-10 h-10 rounded object-cover" alt="">
                            @else
                                <div class="w-10 h-10 rounded bg-gray-200 dark:bg-gray-600"></div>
                            @endif
                            <div>
                                <span class="font-medium block" style="color: var(--text-main)">{{ Str::limit($article->title, 40) }}</span>
                                @if($article->breaking)
                                    <span class="text-xs text-red-500 font-bold uppercase tracking-wider">Breaking</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($article->categoryData)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" style="background-color: {{ $article->categoryData->color }}20; color: {{ $article->categoryData->color }}">
                                {{ $article->categoryData->name }}
                            </span>
                        @else
                            <span class="text-xs text-gray-500">{{ $article->category }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm" style="color: var(--text-sidebar)">
                        {{ $article->author->name ?? 'System' }}
                    </td>
                    <td class="px-6 py-4">
                        <x-status-badge :status="$article->status" />
                    </td>
                    <td class="px-6 py-4 text-xs" style="color: var(--text-sidebar)">
                        {{ $article->published_at ? $article->published_at->format('M d, Y H:i') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <!-- Actions would go here -->
                        <a href="{{ route('news.edit', $article) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                No articles found matching this criteria.
            </div>
        @endif
    </div>
</x-admin-layout>
