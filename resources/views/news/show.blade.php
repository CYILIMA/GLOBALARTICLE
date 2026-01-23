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

    <!-- Comments Section -->
    <section class="bg-gray-50 rounded-xl p-8 mb-12">
        <h3 class="text-xl font-bold mb-6">Comments</h3>

        <!-- Comment Form -->
        <form action="{{ route('comments.store', $news) }}" method="POST" class="bg-white rounded-lg p-6 mb-8 shadow-sm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                    <input type="text" name="author_name" required class="w-full border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                    <input type="email" name="author_email" required class="w-full border-gray-300 rounded-lg">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                <textarea name="content" rows="4" required class="w-full border-gray-300 rounded-lg" placeholder="Share your thoughts..."></textarea>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                Post Comment
            </button>
        </form>

        <!-- Approved Comments -->
        <div class="space-y-4">
            @forelse($news->comments()->approved()->latest()->get() as $comment)
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ substr($comment->author_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $comment->author_name }}</h4>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-gray-700 ml-13">{{ $comment->content }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </section>

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
