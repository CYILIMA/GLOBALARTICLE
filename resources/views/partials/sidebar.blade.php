{{-- Sidebar Component --}}
<aside class="sidebar">
    <h3>Recent Headlines</h3>
    <ul id="latestNews">
        @forelse($sidebarLatest ?? [] as $latest)
            <li>
                <a href="{{ route('news.show', $latest) }}">
                    <span class="headline-title">{{ Str::limit($latest->title, 70) }}</span>
                    <span class="headline-time">{{ $latest->created_at->diffForHumans() }}</span>
                </a>
            </li>
        @empty
             <li style="color: var(--text-muted); font-style: italic; padding: 10px 0;">No latest articles found.</li>
        @endforelse
    </ul>
</aside>
