@extends('layouts.main')

@section('main_class', 'home-main')

@section('content')
  <div class="container {{ isset($category) || request()->route('category') ? 'category-layout' : '' }}">
      <section id="newsArea">
          @if(isset($category))
              <h2>{{ ucfirst($category) }}</h2>
          @else
              <h2 class="theme-strong-text">
                  @php
                      $language = session('news_language', 'english');
                  @endphp
                  @if($language === 'kinyarwanda')
                      AMAKURU MASHYA
                  @elseif($language === 'french')
                      Dernières Nouvelles
                  @else
                      Latest News
                  @endif
              </h2>
          @endif

          @forelse($news as $item)
            <div class="news-card">
                <a href="{{ route('news.show', $item) }}" class="news-thumbnail">
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('images/logo1.png') }}" alt="{{ $item->title }}" loading="lazy">
                </a>
                <div class="news-content">
                    <a href="{{ route('category.show', $item->categoryData->slug ?? Str::slug($item->category)) }}" class="category-link theme-strong-link"><small>{{ $item->categoryData->name ?? $item->category }}</small></a>
                    <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                    <p>{{ Str::limit(strip_tags($item->content), 150) }}</p>
                    <div class="news-meta">
                        By {{ $item->author->name ?? \App\Models\Setting::get('site_name', 'Global Breakings') }} &bull; {{ $item->created_at->diffForHumans() }}
                    </div>
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
      @include('partials.sidebar')
  </div>

  <!-- DOCUMENTARY SECTION -->
  @if($documentaries->count() > 0)
  <section id="documentaryArea">
      <div class="doc-section-inner">

          <!-- Section Header -->
          <div class="doc-section-header">
              <div>
                  <span class="doc-section-eyebrow">Cinematic Discovery</span>
                  <h2 class="doc-section-title">Documentaries</h2>
              </div>
              <a href="{{ route('documentaries.index') }}" class="doc-view-all-btn">
                  View Full Library
                  <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                  </svg>
              </a>
          </div>

          <!-- Cards Grid -->
          <div class="doc-grid">
              @foreach($documentaries as $doc)
              @php
                  $isNews    = $doc instanceof \App\Models\News;
                  $showUrl   = $isNews ? route('news.show', $doc) : route('documentaries.show', $doc);
                  $imageUrl  = $isNews
                      ? ($doc->image     ? asset('storage/' . $doc->image)     : null)
                      : ($doc->thumbnail ? asset('storage/' . $doc->thumbnail) : null);
                  $catName   = $doc->categoryData->name ?? 'Documentary';
                  $isNew     = $doc->published_at && $doc->published_at->greaterThanOrEqualTo(now()->subDays(7));
              @endphp
              <article class="doc-card">
                  <a href="{{ $showUrl }}" class="doc-card-link">

                      <!-- Thumbnail -->
                      <div class="doc-thumb">
                          @if($imageUrl)
                              <img src="{{ $imageUrl }}" alt="{{ $doc->title }}" loading="lazy" class="doc-thumb-img">
                          @else
                              <div class="doc-thumb-fallback">
                                  <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:0.35">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                  </svg>
                              </div>
                          @endif

                          <!-- Always-visible play badge -->
                          <div class="doc-play-badge">
                              <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                                  <path d="M8 5v14l11-7z"/>
                              </svg>
                          </div>

                          <!-- Category label -->
                          <span class="doc-cat-label">{{ $catName }}</span>

                          <!-- NEW badge -->
                          @if($isNew)
                          <span class="doc-new-badge">New</span>
                          @endif
                      </div>

                      <!-- Card Body -->
                      <div class="doc-card-body">
                          <h3 class="doc-card-title">{{ $doc->title }}</h3>
                          <div class="doc-card-meta">
                              <span>{{ number_format($doc->views) }} views</span>
                              <span class="doc-meta-dot"></span>
                              <span>{{ $doc->published_at ? $doc->published_at->diffForHumans() : 'Upcoming' }}</span>
                          </div>
                      </div>

                  </a>
              </article>
              @endforeach
          </div>

      </div>
  </section>
  @endif

  <style>
  /* ── Documentary Home Section ─────────────────────────────── */
  #documentaryArea {
      margin-top: 0;
      padding: 48px 0 56px;
      border-top: 1px solid var(--border-color);
      background: var(--bg-main);
      width: 100%;
  }

  .doc-section-inner {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
  }

  .doc-section-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 28px;
  }

  .doc-section-eyebrow {
      display: inline-block;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 0.18em;
      text-transform: uppercase;
      color: #7c3aed;
      margin-bottom: 6px;
      background: rgba(124,58,237,0.08);
      padding: 3px 10px;
      border-radius: 4px;
  }

  .doc-section-title {
      font-size: clamp(1.5rem, 3vw, 2.25rem);
      font-weight: 900;
      letter-spacing: -0.03em;
      text-transform: uppercase;
      color: var(--text-main);
      line-height: 1;
      margin: 0;
  }

  .doc-view-all-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--text-muted);
      border: 1px solid var(--border-color);
      padding: 8px 14px;
      border-radius: 8px;
      transition: color 0.2s, border-color 0.2s, background 0.2s;
      white-space: nowrap;
  }
  .doc-view-all-btn:hover {
      color: #7c3aed;
      border-color: #7c3aed;
      background: rgba(124,58,237,0.05);
  }

  /* Grid */
  .doc-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
  }

  /* Card */
  .doc-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 14px;
      overflow: hidden;
      transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
  }
  .doc-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(124,58,237,0.12);
      border-color: rgba(124,58,237,0.35);
  }

  .doc-card-link {
      display: block;
      text-decoration: none;
      color: inherit;
  }

  /* Thumbnail */
  .doc-thumb {
      position: relative;
      width: 100%;
      height: 160px;
      overflow: hidden;
      background: var(--border-color);
  }

  .doc-thumb-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.5s ease;
  }
  .doc-card:hover .doc-thumb-img {
      transform: scale(1.06);
  }

  .doc-thumb-fallback {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #3b0764 0%, #1e1b4b 100%);
      color: #a78bfa;
  }

  /* Play badge — always visible, bottom-left */
  .doc-play-badge {
      position: absolute;
      bottom: 10px;
      left: 10px;
      width: 32px;
      height: 32px;
      background: #7c3aed;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      box-shadow: 0 2px 10px rgba(124,58,237,0.5);
      transition: transform 0.2s ease, background 0.2s ease;
      padding-left: 2px;
  }
  .doc-card:hover .doc-play-badge {
      transform: scale(1.12);
      background: #6d28d9;
  }

  /* Category label */
  .doc-cat-label {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 9px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: #fff;
      background: rgba(0,0,0,0.55);
      padding: 3px 8px;
      border-radius: 4px;
  }

  /* NEW badge */
  .doc-new-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      font-size: 9px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: #fff;
      background: #7c3aed;
      padding: 3px 7px;
      border-radius: 4px;
  }

  /* Card body */
  .doc-card-body {
      padding: 14px 16px 16px;
  }

  .doc-card-title {
      font-size: 14px;
      font-weight: 800;
      color: var(--text-main);
      line-height: 1.35;
      margin: 0 0 8px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      transition: color 0.2s;
  }
  .doc-card:hover .doc-card-title {
      color: #7c3aed;
  }

  .doc-card-meta {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 11px;
      font-weight: 600;
      color: var(--text-muted);
  }

  .doc-meta-dot {
      width: 3px;
      height: 3px;
      border-radius: 50%;
      background: var(--text-muted);
      opacity: 0.4;
      flex-shrink: 0;
  }

  /* Responsive */
  @media (max-width: 1024px) {
      .doc-grid { grid-template-columns: repeat(3, 1fr); }
  }

  @media (max-width: 768px) {
      #documentaryArea { padding: 32px 0 40px; }
      .doc-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 12px;
      }
      .doc-thumb { height: 130px; }
      .doc-card-title { font-size: 13px; }
  }

  @media (max-width: 480px) {
      .doc-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 10px;
      }
      .doc-thumb { height: 110px; }
      .doc-section-header { flex-direction: column; align-items: flex-start; }
  }
  </style>
@endsection
