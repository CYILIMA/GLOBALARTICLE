<x-admin-layout>
    @section('header', 'Dashboard Overview')

    <!-- STATS ROW -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Total Articles</div>
            <div class="mt-2 text-3xl font-bold text-gray-800">{{ $totalNews }}</div>
        </div>

        <!-- Total Subscribers -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Total Subscribers</div>
            <div class="mt-2 text-3xl font-bold text-gray-800">{{ $totalSubscribers }}</div>
        </div>

        <!-- Breaking News -->
        <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-red-500">
            <div class="text-gray-500 text-sm font-medium uppercase">Breaking News</div>
            <div class="mt-2 text-3xl font-bold text-gray-800">{{ $breakingCount }}</div>
            <div class="text-xs text-red-500 mt-1">Currently Active</div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex flex-wrap gap-4 mb-8">
        <a href="{{ route('news.create') }}" class="bg-white hover:bg-gray-50 text-blue-600 border border-blue-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-400 dark:border-gray-600 font-bold py-3 px-6 rounded shadow flex items-center gap-2 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Write New Article
        </a>
        <a href="{{ route('admin.subscribers') }}" class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 font-bold py-3 px-6 rounded shadow flex items-center gap-2 transition">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Manage Subscribers
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- LATEST ARTICLES -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Latest Articles</h3>
                <a href="#" class="text-xs text-blue-500 hover:underline">View All</a>
            </div>
            @if($latestArticles->count() > 0)
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($latestArticles as $article)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 dark:text-gray-100 block">{{ Str::limit($article->title, 40) }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $article->category }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $article->created_at->format('M d') }}</span>
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

        <!-- RECENT SUBSCRIBERS -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">New Subscribers</h3>
                <a href="{{ route('admin.subscribers') }}" class="text-xs text-blue-500 hover:underline">Manage</a>
            </div>
            @if($recentSubscribers->count() > 0)
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentSubscribers as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-gray-800 block">{{ $sub->email }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-1 rounded text-xs {{ $sub->status=='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
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
    </div>
</x-admin-layout>
