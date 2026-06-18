@extends('layouts.main')

@section('title', $category->name . ' News | ' . \App\Models\Setting::get('site_name', 'Global Breakings'))
@section('meta_description', $category->description ?: 'Latest ' . $category->name . ' news, updates, and analysis from ' . \App\Models\Setting::get('site_name', 'Global Breakings') . '.')

@section('main_class', 'home-main')

@section('content')
  <div class="container">
      <section id="newsArea">
          <header class="author-hero" style="margin-bottom: 24px;">
              <div class="author-hero-card" style="display:block;">
                  <div class="author-hero-info">
                      <h1>{{ $category->name }}</h1>
                      @if($category->description)
                          <p class="author-hero-description">{{ $category->description }}</p>
                      @else
                          <p class="author-hero-description">Latest {{ $category->name }} news and updates.</p>
                      @endif
                  </div>
              </div>
          </header>

          @forelse($news as $item)
            <div class="news-card">
                @if($item->image)
                    <a href="{{ route('news.show', $item) }}">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy">
                    </a>
                @endif
                <div class="news-content">
                    <a href="{{ route('category.show', $item->categoryData ?? $category) }}" class="category-link theme-strong-link"><small>{{ $item->categoryData->name ?? $item->category }}</small></a>
                    <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                    <p>{{ Str::limit(strip_tags($item->content), 150) }}</p>
                    <div style="margin-top: 10px; font-size: 13px; color: #555;">
                        By {{ $item->author->name ?? \App\Models\Setting::get('site_name', 'Global Breakings') }} &bull; {{ $item->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
          @empty
            <div class="empty-state">
                <p>No articles have been published in {{ $category->name }} yet. Check back soon.</p>
            </div>
          @endforelse

          @if($news->hasPages())
          <div class="pagination-wrapper">
              {{ $news->links() }}
          </div>
          @endif
      </section>

      <!-- SIDEBAR -->
      @include('partials.sidebar')
  </div>
@endsection
