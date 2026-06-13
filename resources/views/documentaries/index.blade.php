@extends('layouts.main')

@section('title', 'Global Documentaries - Discover Global Stories')
@section('meta_description', 'Explore our curated collection of documentaries from around the world, organized by category.')

@section('main_class', 'documentary-main-wrapper')

@section('content')
<div class="documentary-index -mt-12 pt-12 min-h-screen">
    @php 
        $featured = $documentaries->first(); 
        $hasGridItems = ($documentaries->count() > 1) || (!$featured && $documentaries->count() > 0);
        $showEmptyState = !$featured && $documentaries->count() === 0;
    @endphp

    <!-- Hero Section -->
    <section class="relative min-h-[60vh] py-24 flex items-center overflow-hidden doc-hero {{ ($hasGridItems || $showEmptyState) ? 'mb-16' : '' }}">
        @if($featured)
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="{{ $featured->title }}" 
                     class="w-full h-full object-cover opacity-40 scale-105 blur-sm transition-all duration-[3000ms] hover:scale-100">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
            </div>
            
            <div class="relative z-10 px-6 max-w-[1400px] mx-auto">
                <div class="max-w-3xl">
                    <span class="inline-block px-4 py-1 bg-purple-600 text-[11px] font-black uppercase tracking-[0.2em] rounded-full mb-6 animate-pulse">
                        Featured Premiere
                    </span>
                    <h1 class="text-6xl md:text-8xl font-black mb-6 leading-[0.85] tracking-tighter drop-shadow-2xl doc-hero-title">
                        {{ $featured->title }}
                    </h1>
                    <p class="text-xl doc-hero-desc font-medium mb-10 leading-relaxed line-clamp-3">
                        {{ $featured->description }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('documentaries.show', $featured) }}" 
                           class="flex items-center gap-3 bg-white text-black px-8 py-4 rounded-xl font-black uppercase tracking-widest hover:bg-purple-600 hover:text-white transition-all transform hover:scale-105">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            Watch Now
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    @if($hasGridItems || $showEmptyState)
    <!-- Main Body Section -->
    <div class="px-6 max-w-[1400px] mx-auto pb-24">
        <!-- Main Grid -->
        <div class="w-full">
            @if($hasGridItems)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($documentaries as $index => $doc)
                        @if($featured && $doc->id === $featured->id && get_class($doc) === get_class($featured)) @continue @endif
                        
                        @php
                            $isNews = $doc instanceof \App\Models\News;
                            $showUrl = $isNews ? route('news.show', $doc) : route('documentaries.show', $doc);
                            $imageUrl = $isNews ? ($doc->image ? asset('storage/' . $doc->image) : null) : ($doc->thumbnail ? asset('storage/' . $doc->thumbnail) : null);
                        @endphp
                        
                        <article class="group doc-card doc-index-card rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1">
                            <a href="{{ $showUrl }}" class="block no-underline">
                                <!-- Thumbnail -->
                                <div class="doc-index-thumb">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $doc->title }}"
                                             class="doc-index-thumb-img">
                                    @else
                                        <div class="doc-index-thumb-fallback">
                                            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:0.3">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Always-visible play badge -->
                                    <div class="doc-index-play">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>

                                    <!-- Category -->
                                    <span class="doc-index-cat">{{ $doc->categoryData->name ?? 'Documentary' }}</span>
                                </div>

                                <!-- Body -->
                                <div class="p-5">
                                    <h2 class="doc-title text-lg font-black mb-2 leading-snug tracking-tight" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $doc->title }}
                                    </h2>
                                    <p class="doc-desc text-sm font-medium mb-4 leading-relaxed" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $doc->description ?? Str::limit(strip_tags($doc->content), 100) }}
                                    </p>
                                    <div class="flex items-center justify-between text-[11px] font-bold doc-meta uppercase tracking-widest pt-4 border-t doc-card-divider">
                                        <div class="flex items-center gap-2">
                                            <span style="width:6px;height:6px;border-radius:50%;background:#7c3aed;display:inline-block;flex-shrink:0;"></span>
                                            {{ number_format($doc->views) }} Views
                                        </div>
                                        <span>{{ $doc->published_at ? $doc->published_at->format('M Y') : 'Upcoming' }}</span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
                
                <div class="mt-20">
                    {{ $documentaries->links() }}
                </div>
            @elseif($showEmptyState)
                <div class="text-center py-32 doc-empty-state rounded-[3rem] border-dashed">
                    <svg class="w-20 h-20 doc-icon mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <h3 class="text-3xl font-black uppercase italic tracking-tighter mb-4">No Stories Found</h3>
                    <p class="text-gray-500 text-lg">We couldn't find any stories matching your refined criteria.</p>
                    <div class="mt-10">
                        <a href="{{ route('documentaries.index') }}" class="text-purple-400 font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:translate-x-2 transition-transform">
                            Back to Library <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<style>
    /* Documentary Page - Themed Styles */
    .documentary-index {
        background-color: var(--bg-main);
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
    }
    .doc-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
    }
    html.dark .doc-card {
        background: rgba(255,255,255,0.04);
        border-color: rgba(255,255,255,0.06);
    }
    .doc-card:hover {
        border-color: rgba(139,92,246,0.5);
    }
    .doc-title { color: var(--text-main); }
    .doc-title:hover { color: #7c3aed; }
    .doc-desc { color: var(--text-muted); }
    .doc-meta { color: var(--text-muted); }
    .doc-card-divider { border-color: var(--border-color); }

    /* Index card thumbnail */
    .doc-index-thumb {
        position: relative;
        width: 100%;
        height: 190px;
        overflow: hidden;
        background: var(--border-color);
    }
    .doc-index-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .doc-index-card:hover .doc-index-thumb-img {
        transform: scale(1.06);
    }
    .doc-index-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3b0764 0%, #1e1b4b 100%);
        color: #a78bfa;
    }

    /* Play badge */
    .doc-index-play {
        position: absolute;
        bottom: 10px;
        left: 10px;
        width: 36px;
        height: 36px;
        background: #7c3aed;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 2px 10px rgba(124,58,237,0.5);
        transition: transform 0.2s ease;
        padding-left: 2px;
    }
    .doc-index-card:hover .doc-index-play {
        transform: scale(1.12);
    }

    /* Category label */
    .doc-index-cat {
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

    /* Responsive grid */
    @media (max-width: 768px) {
        .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3 {
            gap: 14px !important;
        }
        .doc-index-thumb { height: 150px; }
    }
    html.dark .doc-card-divider { border-color: rgba(255,255,255,0.05); }
    .doc-empty-state {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
    }
    .doc-icon { color: var(--text-muted); opacity: 0.5; }
    /* Hero section — text is white when there's a dark overlay image, theme-aware otherwise */
    .doc-hero { background-color: var(--bg-main); }
    html.dark .doc-hero { background-color: #050505; }
    .doc-hero-title { color: var(--text-main); }
    /* When the featured image is shown, the overlay makes the bg dark so we force white text */
    .doc-hero .absolute + .relative .doc-hero-title { color: #fff; }
    .doc-hero-desc { color: var(--text-muted); }
    .doc-hero .absolute + .relative .doc-hero-desc { color: rgba(255,255,255,0.75); }
    html.dark .doc-hero-title { color: #fff; }
    html.dark .doc-hero-desc { color: rgba(255,255,255,0.7); }
    .prose-xl { font-size: 1.25rem; }
    .pagination { display: flex; justify-content: center; gap: 0.5rem; }
    .page-item { border-radius: 0.5rem; overflow: hidden; }
    .page-link {
        display: block;
        padding: 0.75rem 1.25rem;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-main);
        font-weight: 900;
        transition: all 0.2s;
    }
    .page-link:hover { background: #7c3aed; color: white; border-color: #7c3aed; }
    .active .page-link { background: #7c3aed; border-color: #7c3aed; color: white; }
</style>
@endsection
