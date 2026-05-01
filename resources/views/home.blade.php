@extends('layouts.main')

@section('main_class', 'home-main')

@section('content')
  <div class="container">
      <section id="newsArea">
          @if(isset($category))
              <h2>Category: {{ ucfirst($category) }}</h2>
          @else
              <h2 class="theme-strong-text">Latest News</h2>
          @endif

          @forelse($news as $item)
            <div class="news-card">
                @if($item->image)
                    <a href="{{ route('news.show', $item) }}">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy">
                    </a>
                @endif
                <div class="news-content">
                    <a href="{{ route('home', ['category' => $item->category]) }}" class="category-link theme-strong-link"><small>{{ $item->category }}</small></a>
                    <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                    <p>{{ Str::limit(strip_tags($item->content), 150) }}</p>
                    <div style="margin-top: 10px; font-size: 13px; color: #555;">
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

  <!-- DOCUMENTARY SECTION (Full Width Breakout) -->
  @if($documentaries->count() > 0)
  <section id="documentaryArea" class="mt-0 py-20 border-t border-gray-100 dark:border-white/5 w-full bg-white dark:bg-[#0a0a0a]">
      <div class="max-w-7xl mx-auto">
          <div class="flex items-end justify-between mb-16 px-4">
              <div>
                  <span class="inline-block px-3 py-1 bg-purple-600/10 text-purple-600 dark:text-purple-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-md mb-4">
                      Cinematic Discovery
                  </span>
                  <h2 class="text-5xl font-black text-gray-900 dark:text-white tracking-tighter uppercase leading-none">Documentaries</h2>
              </div>
              <a href="{{ route('documentaries.index') }}" class="group flex items-center gap-3 text-[11px] font-black text-gray-400 hover:text-purple-600 transition-colors uppercase tracking-widest">
                  View Full Library
                  <div class="w-8 h-8 rounded-full border border-gray-200 dark:border-white/10 flex items-center justify-center group-hover:border-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all">
                      <svg class="w-4 h-4 text-inherit" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                  </div>
              </a>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 px-4">
              @foreach($documentaries as $doc)
                @php
                    $isNews = $doc instanceof \App\Models\News;
                    $showUrl = $isNews ? route('news.show', $doc) : route('documentaries.show', $doc);
                    $imageUrl = $isNews ? ($doc->image ? asset('storage/' . $doc->image) : null) : ($doc->thumbnail ? asset('storage/' . $doc->thumbnail) : null);
                @endphp
              <article class="group relative bg-white dark:bg-[#0a0a0a] rounded-[2rem] overflow-hidden shadow-[0_20px_50px_-15px_rgba(0,0,0,0.05)] dark:shadow-none hover:shadow-[0_40px_80px_-20px_rgba(139,92,246,0.15)] transition-all duration-700 hover:-translate-y-3 border border-gray-100 dark:border-white/5">
                  <a href="{{ $showUrl }}" class="block no-underline">
                      <div class="aspect-[16/10] relative overflow-hidden bg-gray-100 dark:bg-gray-800">
                          @if($imageUrl)
                              <img src="{{ $imageUrl }}" alt="{{ $doc->title }}" loading="lazy"
                                   class="w-full h-full object-cover group-hover:scale-110 group-hover:rotate-1 transition duration-1000 grayscale-[20%] group-hover:grayscale-0">
                          @else
                              <div class="absolute inset-0 bg-gradient-to-br from-purple-900/20 to-black/40"></div>
                          @endif

                          <!-- Overlay with Play Icon -->
                          <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500"></div>
                          <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                              <div class="w-16 h-16 bg-white/10 backdrop-blur-xl rounded-full flex items-center justify-center border border-white/20 transform scale-50 group-hover:scale-100 transition-all duration-500 shadow-2xl">
                                  <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                              </div>
                          </div>

                          <!-- Pulse Badge -->
                          <div class="absolute top-6 left-6 flex items-center gap-3">
                              @if($doc->published_at && $doc->published_at->greaterThanOrEqualTo(now()->subDays(7)))
                              <span class="flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                              </span>
                              <span class="px-3 py-1 bg-black/40 backdrop-blur-md rounded-full text-[9px] font-black uppercase tracking-widest text-white border border-white/10">
                                  New Story
                              </span>
                              @endif
                          </div>
                      </div>

                      <div class="p-8">
                          <div class="flex items-center gap-3 mb-5">
                              <span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">{{ $doc->categoryData->name ?? 'Documentary' }}</span>
                              <div class="w-1 h-1 bg-gray-300 dark:bg-white/20 rounded-full"></div>
                              <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ number_format($doc->views) }} Views</span>
                          </div>

                          <h3 class="text-2xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2 leading-[1.1] mb-6 tracking-tighter">
                              {{ $doc->title }}
                          </h3>

                          <div class="flex items-center justify-between pt-6 border-t border-gray-50 dark:border-white/5">
                              <div class="flex items-center gap-3">
                                  @if($doc->author)
                                  <div class="w-6 h-6 rounded-full bg-purple-600/10 flex items-center justify-center text-[10px] font-black text-purple-600 dark:text-purple-400">
                                      {{ strtoupper(substr($doc->author->name, 0, 1)) }}
                                  </div>
                                  <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $doc->author->name }}</span>
                                  @endif
                              </div>
                              <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">{{ $doc->published_at ? $doc->published_at->diffForHumans() : 'Upcoming' }}</span>
                          </div>
                      </div>
                  </a>
              </article>
              @endforeach
          </div>
      </div>
  </section>
  @endif
@endsection
