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
                    <span class="headline-title">{{ Str::limit($latest->title, 70) }}</span>
                    <span class="headline-time">{{ $latest->created_at->diffForHumans() }}</span>
                </a>
            </li>
        @empty
             <li style="color: var(--text-muted); font-style: italic; padding: 10px 0;">No latest articles found.</li>
        @endforelse
    </ul>
  </aside>

  <!-- DOCUMENTARY SECTION -->
  @if($documentaries->count() > 0)
  <section id="documentaryArea" style="margin-top: 40px; grid-column: 1 / -1;">
      <h2>Featured Documentaries</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
          @foreach($documentaries as $documentary)
          <a href="{{ route('documentaries.show', $documentary) }}" class="news-card" style="text-decoration: none; transition: transform 0.3s;">
              @if($documentary->thumbnail)
                  <img src="{{ asset('storage/' . $documentary->thumbnail) }}" alt="{{ $documentary->title }}" loading="lazy" style="border-radius: 8px;">
              @else
                  <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; border-radius: 8px; display: flex; align-items: center; justify-center; color: white; font-size: 48px;">
                      🎬
                  </div>
              @endif
              <div class="news-content">
                  <small style="color: #667eea; font-weight: bold;">DOCUMENTARY</small>
                  <h3>{{ $documentary->title }}</h3>
                  <p>{{ Str::limit($documentary->description, 120) }}</p>
                  <div style="margin-top: 10px; font-size: 13px; color: #555; display: flex; justify-content: space-between;">
                      <span>{{ $documentary->published_at->diffForHumans() }}</span>
                      <span>👁️ {{ number_format($documentary->views) }} views</span>
                  </div>
              </div>
          </a>
          @endforeach
      </div>
  </section>
  @endif
@endsection
