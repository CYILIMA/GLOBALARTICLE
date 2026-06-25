@extends('layouts.main')

@section('title', $author->name . ' — Author Articles')
@section('meta_description', 'Read all articles written by ' . $author->name)
@section('main_class', 'page-full-width')

@section('content')
<section class="author-detail-wrapper">
    <aside class="author-profile-panel">
        <div class="author-profile-card">
            @if($author->profile_image)
                <img src="{{ asset('storage/' . $author->profile_image) }}" alt="{{ $author->name }}" class="author-profile-image">
            @else
                <span class="author-profile-avatar">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
            @endif
            <div class="author-profile-info">
                <h1>{{ $author->name }}</h1>
                <p class="author-profile-description">
                    {{ $author->profile_description ?: 'More details coming soon…' }}
                </p>
                <p class="author-profile-meta">{{ $articles->count() }} published article{{ $articles->count() === 1 ? '' : 's' }}</p>
            </div>
        </div>
    </aside>

    <main class="author-articles-list">
        @if($articles->count())
            @foreach($articles as $article)
                <a href="{{ route('news.show', $article) }}" class="author-article-row">
                    @if($article->image)
                        <div class="author-article-row-thumb">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                        </div>
                    @endif
                    <div class="author-article-row-content">
                        <span class="author-article-row-category">{{ strtoupper($article->categoryData->name ?? $article->category) }}</span>
                        <h2>{{ $article->title }}</h2>
                        <span class="author-article-row-date">{{ $article->published_at?->diffForHumans() ?? $article->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @endforeach
        @else
            <div class="empty-state">
                <p>No published articles were found for this author yet.</p>
            </div>
        @endif
    </main>
</section>
@endsection
