@extends('layouts.main')

@section('content')
  <section id="newsArea">
      @if(isset($category))
          <h2>Category: {{ ucfirst($category) }}</h2>
      @else
          <h2>Latest News</h2>
      @endif

      @forelse($news as $item)
        <div class="news-card">
            @if($item->image)
                <a href="{{ route('news.show', $item) }}">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy">
                </a>
            @endif
            <div class="news-content">
                <a href="{{ route('home', ['category' => $item->category]) }}" class="category-link"><small>{{ $item->category }}</small></a>
                <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                <p>{{ Str::limit(strip_tags($item->content), 150) }}</p>
                <div style="margin-top: 10px; font-size: 13px; color: #555;">{{ $item->created_at->diffForHumans() }}</div>
            </div>
        </div>
      @empty
        <div class="news-card" style="padding: 40px; text-align: center;">
            <h3>No News Yet</h3>
            <p>Check back later or subscribe for updates.</p>
        </div>
      @endforelse
  </section>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h3>Recent Headlines</h3>
    <ul id="latestNews">
        @forelse($sidebarLatest ?? [] as $latest)
            <li>
                <a href="{{ route('news.show', $latest) }}">
                    <span style="display:block; font-weight:500;">{{ Str::limit($latest->title, 60) }}</span>
                    <small style="color:#666; font-size:11px;">{{ $latest->created_at->diffForHumans() }}</small>
                </a>
            </li>
        @empty
             <li style="color: var(--text-muted); font-style: italic; padding: 10px 0;">No latest articles found.</li>
        @endforelse
    </ul>
  </aside>
@endsection
