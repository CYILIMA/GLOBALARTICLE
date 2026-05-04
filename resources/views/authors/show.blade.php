@extends('layouts.main')

@section('title', $author->name . ' — Author Articles')
@section('meta_description', 'Read all articles written by ' . $author->name)
@section('main_class', 'page-full-width')

@section('content')
<section class="author-hero">
    <div class="author-hero-card">
        @if($author->profile_image)
            <img src="{{ asset('storage/' . $author->profile_image) }}" alt="{{ $author->name }}" class="author-hero-image">
        @else
            <span class="author-hero-avatar">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
        @endif
        <div class="author-hero-info">
            <h1>{{ $author->name }}</h1>
            <p class="author-hero-description">
                {{ $author->profile_description ?: 'Explore articles and insights from this author on Global Breakings.' }}
            </p>
        </div>
    </div>
</section>

<section class="author-articles-section">
    @if($articles->count())
        <div class="author-articles-grid">
            @foreach($articles as $article)
                <a href="{{ route('news.show', $article) }}" class="author-article-card">
                    @if($article->image)
                        <div class="author-article-thumb">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                        </div>
                    @endif
                    <div class="author-article-content">
                        <h2>{{ $article->title }}</h2>
                        <span>{{ $article->published_at?->format('F d, Y') ?? $article->created_at->format('F d, Y') }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $articles->links() }}
        </div>
    @else
        <div class="empty-state">
            <p>No published articles were found for this author yet.</p>
        </div>
    @endif
</section>
@endsection
