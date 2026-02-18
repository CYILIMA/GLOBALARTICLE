@extends('layouts.main')

@section('title', 'Global Documentaries - Discover Global Stories')
@section('meta_description', 'Explore our curated collection of documentaries from around the world, organized by category.')

@section('main_class', 'documentary-main-wrapper')

@section('content')
<div class="documentary-index bg-black -mt-12 pt-12 min-h-screen text-white">
    @php 
        $featured = $documentaries->first(); 
        $hasGridItems = ($documentaries->count() > 1) || (!$featured && $documentaries->count() > 0);
        $showEmptyState = !$featured && $documentaries->count() === 0;
    @endphp

    <!-- Hero Section -->
    <section class="relative min-h-[60vh] py-24 flex items-center overflow-hidden {{ ($hasGridItems || $showEmptyState) ? 'mb-16' : '' }}">
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
                    <h1 class="text-6xl md:text-8xl font-black mb-6 leading-[0.85] tracking-tighter drop-shadow-2xl">
                        {{ $featured->title }}
                    </h1>
                    <p class="text-xl text-gray-300 font-medium mb-10 leading-relaxed line-clamp-3">
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
                        
                        <article class="group relative bg-white/5 rounded-[2.5rem] overflow-hidden border border-white/5 hover:border-purple-500/50 transition-all duration-500 hover:shadow-[0_40px_80px_-20px_rgba(139,92,246,0.3)]">
                            <a href="{{ $showUrl }}" class="block no-underline">
                                <div class="aspect-video relative overflow-hidden">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $doc->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-[1.5s] group-hover:scale-110">
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-purple-900 to-black"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-full flex items-center justify-center border border-white/20 transform scale-50 group-hover:scale-100 transition-all duration-500 shadow-2xl">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                    <div class="absolute top-6 left-6">
                                        <span class="px-4 py-1.5 bg-black/50 backdrop-blur-md border border-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-purple-400">
                                            {{ $doc->categoryData->name ?? 'Documentary' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-8">
                                    <h2 class="text-3xl font-black mb-4 leading-tight group-hover:text-purple-400 transition-colors tracking-tighter">
                                        {{ $doc->title }}
                                    </h2>
                                    <p class="text-gray-400 text-sm font-medium mb-6 line-clamp-2 leading-relaxed">
                                        {{ $doc->description ?? Str::limit(strip_tags($doc->content), 120) }}
                                    </p>
                                    <div class="flex items-center justify-between text-[11px] font-black text-gray-500 uppercase tracking-widest pt-6 border-t border-white/5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
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
                <div class="text-center py-32 bg-white/5 rounded-[3rem] border border-white/10 border-dashed">
                    <svg class="w-20 h-20 text-gray-700 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
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
    /* Custom styles for Netflix aesthetic */
    .documentary-index {
        background-color: #050505;
        font-family: 'Outfit', sans-serif;
    }
    .prose-xl { font-size: 1.25rem; }
    .pagination { @apply flex justify-center gap-2; }
    .page-item { @apply rounded-lg overflow-hidden; }
    .page-link { @apply px-5 py-3 bg-white/5 border border-white/10 text-white font-black transition-all hover:bg-purple-600; }
    .active .page-link { @apply bg-purple-600 border-purple-600; }
</style>
@endsection
