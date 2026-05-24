@extends('layouts.main')

@section('title', $documentary->title)
@section('meta_description', $documentary->description)
@section('meta_image', $documentary->thumbnail ? asset('storage/' . $documentary->thumbnail) : asset('images/logo1.png'))

@section('main_class', 'documentary-main-wrapper')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex flex-col gap-16">
        <!-- Main Content -->
        <article class="flex-1 min-w-0">
            <!-- Header -->
            <header class="mb-10">
                <div class="flex items-center gap-3 mb-6">
                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xs font-black uppercase tracking-widest rounded-full shadow-sm">
                        Documentary
                    </span>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <time datetime="{{ $documentary->published_at }}" class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">
                        {{ $documentary->published_at ? $documentary->published_at->format('F d, Y') : 'Upcoming' }}
                    </time>
                    <div class="ml-auto flex items-center gap-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                            {{ number_format($documentary->views) }} Views
                        </div>
                        @if($documentary->author)
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400">
                            By {{ $documentary->author->name }}
                        </div>
                        @endif
                    </div>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-gray-900 dark:text-white leading-[0.9] mb-8 tracking-tighter">
                    {{ $documentary->title }}
                </h1>

                <div class="flex flex-col md:flex-row md:items-center gap-6 mb-10 text-gray-600 dark:text-gray-400">
                    <p class="text-xl font-medium leading-relaxed border-l-4 border-purple-500 pl-6 italic flex-1">
                        {{ $documentary->description }}
                    </p>
                </div>

                <!-- Video Embed Container -->
                @if(trim($documentary->video_url))
                    <div class="mb-12 rounded-[2rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] bg-black group relative aspect-video transform transition-all hover:scale-[1.01] duration-500">
                        @php
                            $videoId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $documentary->video_url, $matches)) {
                                $videoId = $matches[1];
                            }
                        @endphp

                        @if($videoId)
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&autoplay=0"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                    class="absolute inset-0 w-full h-full transition-opacity duration-700 opacity-95 group-hover:opacity-100">
                            </iframe>
                        @elseif(str_contains($documentary->video_url, 'vimeo.com'))
                            @php
                                preg_match('/vimeo\.com\/(\d+)/', $documentary->video_url, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp
                            @if($videoId)
                                <iframe src="https://player.vimeo.com/video/{{ $videoId }}?color=a855f7&title=0&byline=0&portrait=0"
                                        frameborder="0"
                                        allow="autoplay; fullscreen; picture-in-picture"
                                        allowfullscreen
                                        class="absolute inset-0 w-full h-full transition-opacity duration-700 opacity-95 group-hover:opacity-100">
                                </iframe>
                            @endif
                        @else
                            <video controls class="absolute inset-0 w-full h-full object-cover">
                                <source src="{{ $documentary->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                @elseif($documentary->thumbnail)
                    <figure class="mb-12 rounded-[2rem] overflow-hidden shadow-2xl group relative">
                        <img src="{{ asset('storage/' . $documentary->thumbnail) }}" alt="{{ $documentary->title }}"
                             class="w-full h-auto object-cover max-h-[700px] transition-transform duration-1000 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </figure>
                @endif
            </header>

            <!-- Content Body -->
            <div class="prose prose-2xl prose-indigo dark:prose-invert max-w-none mb-16 text-gray-800 dark:text-gray-300 leading-normal font-serif drop-cap">
                {!! $documentary->content !!}
            </div>

            <!-- Related Documentaries (Bottom Grid) -->
            @if($relatedDocumentaries->count() > 0)
                <section class="mt-20 border-t border-gray-100 dark:border-gray-800 pt-16">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">Next Up</h3>
                        <div class="h-px flex-1 bg-gradient-to-r from-gray-100 to-transparent dark:from-gray-800 ml-8"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($relatedDocumentaries as $related)
                            <a href="{{ route('documentaries.show', $related) }}" class="group block no-underline">
                                <div class="aspect-video rounded-3xl overflow-hidden mb-6 shadow-xl bg-gray-100 dark:bg-gray-800 relative transform transition-transform group-hover:-translate-y-2 duration-500">
                                    @if($related->thumbnail)
                                        <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->title }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-indigo-600/20"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/30 transform scale-50 group-hover:scale-100 transition-transform duration-500">
                                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors leading-tight mb-3 tracking-tight">
                                    {{ $related->title }}
                                </h4>
                                <div class="flex items-center gap-3 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                    <span>{{ $related->published_at ? $related->published_at->diffForHumans() : 'Upcoming' }}</span>
                                    <span class="w-1 h-1 bg-gray-300 dark:bg-gray-700 rounded-full"></span>
                                    <span>{{ number_format($related->views) }} views</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>
    </div>
</div>

@endsection
