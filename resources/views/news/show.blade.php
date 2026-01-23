@extends('layouts.main')

@section('title', $news->meta_title ?? $news->title)
@section('meta_description', $news->meta_description ?? Str::limit(strip_tags($news->content), 150))

@section('content')
<article class="max-w-4xl mx-auto">
    <!-- Header -->
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-4 text-sm">
            <a href="{{ route('home', ['category' => $news->category]) }}" class="text-blue-600 font-bold uppercase hover:underline">
                {{ $news->category }}
            </a>
            <span class="text-gray-400">&bull;</span>
            <time datetime="{{ $news->published_at ?? $news->created_at }}" class="text-gray-500">
                {{ ($news->published_at ?? $news->created_at)->format('F d, Y') }}
            </time>
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
            {{ $news->title }}
        </h1>

        @if($news->image)
            <figure class="mb-8 rounded-xl overflow-hidden shadow-lg">
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="w-full h-auto object-cover max-h-[600px]">
            </figure>
        @endif
    </header>

    <!-- Content -->
    <div class="prose prose-lg prose-blue max-w-none mb-12 text-gray-800 leading-relaxed">
        {!! $news->content !!}
    </div>

    <!-- Share / Footer (Simplified) -->
    <div class="border-t border-gray-200 pt-6 mb-12">
        <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">Share this article</h4>
        <div class="flex gap-4">
            <!-- Placeholders for social share -->
            <a href="#" class="text-gray-400 hover:text-blue-600 transition"><i class="fab fa-twitter text-xl"></i></a>
            <a href="#" class="text-gray-400 hover:text-blue-800 transition"><i class="fab fa-facebook text-xl"></i></a>
            <a href="#" class="text-gray-400 hover:text-blue-500 transition"><i class="fab fa-linkedin text-xl"></i></a>
        </div>
    </div>

    <!-- Related News -->
    @if($relatedNews->count() > 0)
        <section class="bg-gray-50 rounded-xl p-8 mb-12">
            <h3 class="text-xl font-bold mb-6 border-b pb-2">More in {{ $news->category }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedNews as $related)
                    <a href="{{ route('news.show', $related) }}" class="group block">
                        @if($related->image)
                            <div class="aspect-video rounded-lg overflow-hidden mb-3">
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                        @endif
                        <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition leading-snug">
                            {{ $related->title }}
                        </h4>
                        <span class="text-xs text-gray-500 mt-1 block">{{ $related->created_at->diffForHumans() }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</article>
@endsection
