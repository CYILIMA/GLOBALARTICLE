<x-admin-layout>
    @section('header', 'Article Performance Analytics')

    <!-- OVERVIEW STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-6 rounded-xl shadow-sm border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="text-xs font-black uppercase tracking-[0.2em] mb-2" style="color: var(--text-sidebar)">Total Reach</div>
            <div class="text-4xl font-black" style="color: var(--text-main)">{{ number_format($totalViews) }}</div>
            <div class="mt-2 text-xs font-bold text-blue-500 bg-blue-500/10 px-2 py-0.5 rounded-full w-fit">Total Views</div>
        </div>

        <div class="p-6 rounded-xl shadow-sm border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="text-xs font-black uppercase tracking-[0.2em] mb-2" style="color: var(--text-sidebar)">Avg. Engagement</div>
            <div class="text-4xl font-black" style="color: var(--text-main)">{{ number_format($averageViews, 1) }}</div>
            <div class="mt-2 text-xs font-bold text-green-500 bg-green-500/10 px-2 py-0.5 rounded-full w-fit">Views per Article</div>
        </div>

        <div class="p-6 rounded-xl shadow-sm border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="text-xs font-black uppercase tracking-[0.2em] mb-2" style="color: var(--text-sidebar)">7-Day Momentum</div>
            <div class="text-4xl font-black" style="color: var(--text-main)">+{{ number_format($recentViews) }}</div>
            <div class="mt-2 text-xs font-bold text-purple-500 bg-purple-500/10 px-2 py-0.5 rounded-full w-fit">Recent Views</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- TOP ARTICLES -->
        <div class="lg:col-span-2 rounded-xl shadow-sm overflow-hidden border transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="px-6 py-4 border-b flex justify-between items-center" style="background: var(--sidebar-hover); border-color: var(--border-color)">
                <h3 class="font-bold text-lg" style="color: var(--text-main)">Top 10 Performers</h3>
                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Global Ranking</span>
            </div>
            <table class="w-full text-left">
                <thead class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b" style="border-color: var(--border-color)">
                    <tr>
                        <th class="px-6 py-3">Article Title</th>
                        <th class="px-6 py-3 text-right">Views</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-color)">
                    @foreach($topArticles as $article)
                    <tr class="hover:opacity-80 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-black text-gray-500">#{{ $loop->iteration }}</span>
                                <div>
                                    <span class="font-bold text-sm block" style="color: var(--text-main)">{{ Str::limit($article->title, 60) }}</span>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-500">{{ $article->category }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-lg" style="color: var(--text-main)">{{ number_format($article->views) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- VIEWS BY CATEGORY -->
        <div class="rounded-xl shadow-sm border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="px-6 py-4 border-b" style="background: var(--sidebar-hover); border-color: var(--border-color)">
                <h3 class="font-bold" style="color: var(--text-main)">Performance by Category</h3>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($categoryStats as $cat)
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-black uppercase tracking-widest" style="color: var(--text-sidebar)">{{ $cat->name }}</span>
                            <span class="text-sm font-black" style="color: var(--text-main)">{{ number_format($cat->total_views ?? 0) }}</span>
                        </div>
                        <div class="h-2 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000" 
                                 style="width: {{ $totalViews > 0 ? (($cat->total_views ?? 0) / $totalViews) * 100 : 0 }}%; background-color: {{ $cat->color ?? '#3b82f6' }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
