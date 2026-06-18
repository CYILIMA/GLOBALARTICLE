@extends('layouts.main')

@section('title', $news->meta_title ?? $news->title)
@section('meta_description', $news->meta_description ?? Str::limit(strip_tags($news->content), 150))

@section('content')
<article class="article-container">
    <header class="article-header">
        <h1 class="article-title">
            {{ $news->title }}
        </h1>

        <div class="article-meta">
            @if($news->categoryData)
                <a href="{{ route('category.show', $news->categoryData) }}" class="article-category-link">
                    {{ $news->categoryData->name }}
                </a>
            @else
                <span class="article-category-link">{{ $news->category }}</span>
            @endif
            <span class="article-meta-separator">&bull;</span>
            <time datetime="{{ $news->published_at ?? $news->created_at }}" class="article-meta-time">
                {{ ($news->published_at ?? $news->created_at)->format('F d, Y • h:i A') }}
            </time>
        </div>

        @if($news->image)
            <figure class="article-image-container">
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="article-image">
                @if($news->image_source)
                    <figcaption class="article-image-caption text-sm text-gray-500 dark:text-gray-400 mt-2">Image source: {{ $news->image_source }}</figcaption>
                @endif
            </figure>
        @endif

        @if($news->author)
            <div class="article-author-card">
                <a href="{{ route('authors.show', $news->author) }}" class="article-author-link-card">
                    @if($news->author->profile_image)
                        <img src="{{ asset('storage/' . $news->author->profile_image) }}" alt="{{ $news->author->name }}" class="article-author-image">
                    @else
                        <span class="article-author-avatar">{{ strtoupper(substr($news->author->name, 0, 1)) }}</span>
                    @endif
                    <div class="article-author-details">
                        <span class="article-author-name">{{ $news->author->name }}</span>
                    </div>
                </a>
            </div>
        @endif
    </header>

    {{-- In-Article Banner Ad --}}
    @if(!empty($globalInArticleAds))
    <div class="ad-in-article-zone" id="adInArticle"
         data-ads="{{ json_encode($globalInArticleAds) }}"
         data-img-class="ad-in-article-img">
        <span class="ad-label-tag">Advertisement</span>
        <div class="ad-rotator-slot"></div>
        <button class="ad-close-btn" onclick="this.closest('.ad-in-article-zone').remove()" title="Close" aria-label="Close advertisement">✕</button>
    </div>
    @endif

    <!-- Content -->
    <div class="article-content">
        @if($news->content_version === 'v2' && $news->content_blocks)
            {{-- Render block-based content --}}
            @foreach($news->content_blocks['blocks'] ?? [] as $index => $block)
                @if($block['type'] === 'paragraph')
                    <p class="{{ $index === 0 ? 'article-intro' : '' }}">
                        {!! $block['data']['text'] ?? '' !!}
                    </p>

                @elseif($block['type'] === 'header')
                    @if($block['data']['level'] == 2)
                        <h2 class="article-heading-2">{{ $block['data']['text'] ?? '' }}</h2>
                    @elseif($block['data']['level'] == 3)
                        <h3 class="article-heading-3">{{ $block['data']['text'] ?? '' }}</h3>
                    @endif

                @elseif($block['type'] === 'image')
                    <figure class="article-figure">
                        <img src="{{ $block['data']['file']['url'] ?? '' }}"
                             alt="{{ $block['data']['caption'] ?? '' }}"
                             class="article-figure-image">
                        @if(!empty($block['data']['caption']))
                            <figcaption class="article-figcaption">
                                {{ $block['data']['caption'] }}
                            </figcaption>
                        @endif
                    </figure>

                @elseif($block['type'] === 'quote')
                    <blockquote class="article-quote">
                        <p>{{ $block['data']['text'] ?? '' }}</p>
                        @if(!empty($block['data']['caption']))
                            <cite>{{ $block['data']['caption'] }}</cite>
                        @endif
                    </blockquote>
                @elseif($block['type'] === 'link')
                    <div class="article-link-block">
                        <a href="{{ $block['data']['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                            {{ $block['data']['text'] ?? 'Read more' }}
                        </a>
                    </div>
                @endif
            @endforeach
        @else
            {{-- Render legacy HTML content --}}
            {!! $news->content !!}
        @endif
    </div>

    <!-- Comments Section -->
    <section class="article-section">
        <h3 class="article-section-title">Comments</h3>

        <!-- Comment Form -->
        <form action="{{ route('comments.store', $news) }}" method="POST" class="comment-form">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="author_name" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Your Email</label>
                    <input type="email" name="author_email" required class="form-input">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Comment</label>
                <textarea name="content" required class="form-textarea" placeholder="Share your thoughts..."></textarea>
            </div>
            <button type="submit" class="btn-primary">
                Post Comment
            </button>
        </form>

        <!-- Approved Comments -->
        <div class="comments-list">
            @forelse($news->comments()->approved()->latest()->get() as $comment)
                <div class="comment-card">
                    <div class="comment-header">
                        <div class="comment-avatar">
                            {{ substr($comment->author_name, 0, 1) }}
                        </div>
                        <div class="comment-info">
                            <h4 class="comment-author">{{ $comment->author_name }}</h4>
                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="comment-content">{{ $comment->content }}</p>
                </div>
            @empty
                <p class="empty-state">No comments yet. Be the first to comment!</p>
            @endforelse
        </div>
    </section>

    <!-- Related News -->
    @if($relatedNews->count() > 0)
        <section class="article-section">
            <h3 class="article-section-title">More in {{ $news->category }}</h3>
            <div class="related-grid">
                @foreach($relatedNews as $related)
                    <a href="{{ route('news.show', $related) }}" class="related-card">
                        @if($related->image)
                            <div class="related-image-container">
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="related-image">
                            </div>
                        @endif
                        <h4 class="related-title">
                            {{ $related->title }}
                        </h4>
                        <span class="related-time">{{ $related->created_at->diffForHumans() }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</article>

@include('partials.sidebar')
@endsection
