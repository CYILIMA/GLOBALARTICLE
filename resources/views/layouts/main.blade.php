<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>@yield('title', 'GLOBAL BREAKINGS | Breaking & Latest News')</title>
  <meta name="description" content="@yield('meta_description', 'Global Breakings brings you the latest breaking news from around the world.')" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta property="og:title" content="@yield('title', 'GLOBAL BREAKINGS | Breaking & Latest News')" />
  <meta property="og:description" content="@yield('meta_description', 'Global Breakings brings you the latest breaking news from around the world.')" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="{{ url()->current() }}" />
  <meta property="og:image" content="@yield('meta_image', asset('images/logo1.png'))" />
  <meta property="twitter:card" content="summary_large_image" />
  <meta property="twitter:title" content="@yield('title', 'GLOBAL BREAKINGS | Breaking & Latest News')" />
  <meta property="twitter:description" content="@yield('meta_description', 'Global Breakings brings you the latest breaking news from around the world.')" />
  <meta property="twitter:image" content="@yield('meta_image', asset('images/logo1.png'))" />

  <!-- CSS FILE -->
  <link rel="stylesheet" href="{{ asset('css/CSSONLY.css') }}?v={{ time() }}">

  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            purple: {
              50: '#f5f3ff',
              100: '#ede9fe',
              200: '#ddd6fe',
              300: '#c4b5fd',
              400: '#a78bfa',
              500: '#8b5cf6',
              600: '#7c3aed',
              700: '#6d28d9',
              800: '#5b21b6',
              900: '#4c1d95',
              950: '#2e1065',
            },
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
            serif: ['Merriweather', 'serif'],
          }
        }
      }
    }
  </script>
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3769891956372409"
     crossorigin="anonymous"></script>
</head>
<body class="@if(isset($globalBreakingNews) && $globalBreakingNews->count() > 0) has-breaking-banner @elseif(session('success')) has-success-banner @endif">

<header>
  @if(!empty($globalHeaderAds))
  <div class="ad-leaderboard-zone" id="adLeaderboard"
       data-ads="{{ json_encode($globalHeaderAds) }}"
       data-img-class="ad-banner-img"
       data-link-class="ad-banner-link">
      <span class="ad-label-tag">Advertisement</span>
      <div class="ad-rotator-slot"></div>
      <button class="ad-close-btn" onclick="dismissAd('adLeaderboard')" title="Close advertisement" aria-label="Close advertisement">✕</button>
  </div>
  @endif

  <div class="top-bar">
    <div class="logo" onclick="window.location='{{ url('/') }}'">
      <img src="{{ asset('images/logo1.png') }}" alt="Global Breakings" class="logo-icon">
      <span class="logo-main">GLOBAL</span>
      <span class="logo-highlight">BREAKINGS</span>
    </div>

    <nav class="desktop-nav">
      <ul>
        <li><a href="{{ url('/') }}" class="theme-nav-link {{ request()->routeIs('home') && !request()->has('category') ? 'active' : '' }}">HOME</a></li>
        @if(isset($globalCategories))
            @foreach($globalCategories->where('slug', '!=', 'documentary')->take(7) as $cat)
                <li>
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="theme-nav-link {{ request()->route('category') == $cat->slug || request('category') == $cat->slug ? 'active' : '' }}">
                       {{ strtoupper($cat->name) }}
                    </a>
                </li>
            @endforeach

            @if($globalCategories->count() > 7)
                <li class="nav-dropdown">
                    <button type="button" class="nav-dropdown-trigger theme-nav-link" aria-expanded="false" aria-controls="moreCategoriesMenu">MORE</button>
                    <div class="nav-dropdown-content" id="moreCategoriesMenu">
                        @foreach($globalCategories->where('slug', '!=', 'documentary')->skip(7) as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}"
                               class="theme-nav-link {{ request()->route('category') == $cat->slug || request('category') == $cat->slug ? 'active' : '' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </li>
            @endif
            <li>
                <a href="{{ route('documentaries.index') }}"
                   class="theme-nav-link {{ request()->routeIs('documentaries.*') ? 'active' : '' }}">
                   DOCUMENTARY
                </a>
            </li>
        @endif
      </ul>
    </nav>

    <div class="header-right">
        <!-- Theme Toggle -->
        <button id="themeToggle" class="theme-toggle-btn" title="Toggle Theme">
            <span id="themeIcon">🌙</span>
        </button>

        <!-- Hamburger -->
        <div class="hamburger" onclick="toggleMenu()">☰</div>
    </div>
    <!-- Mobile Nav Overlay -->
    <div id="navOverlay" onclick="toggleMenu()"></div>

    <!-- Mobile Nav Drawer -->
    <nav id="mobileNav">
      <div class="mobile-nav-header">
        <a href="{{ url('/') }}" class="mobile-nav-logo">
          <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="logo-icon">
          <span class="logo-main">GLOBAL</span>
          <span class="logo-highlight">BREAKINGS</span>
        </a>
        <div class="close-btn" onclick="toggleMenu()">✕</div>
      </div>
      <ul>
        <li><a href="{{ url('/') }}" class="{{ request()->routeIs('home') && !request()->has('category') ? 'active' : '' }}">HOME</a></li>
        @if(isset($globalCategories))
            @foreach($globalCategories as $cat)
                <li>
                    <a href="{{ route('category.show', $cat->slug) }}"
                       class="{{ request()->route('category') == $cat->slug || request('category') == $cat->slug ? 'active' : '' }}">
                       {{ strtoupper($cat->name) }}
                    </a>
                </li>
            @endforeach
            <li>
                <a href="{{ route('documentaries.index') }}"
                   class="{{ request()->routeIs('documentaries.*') ? 'active' : '' }}">
                   DOCUMENTARY
                </a>
            </li>
        @endif
      </ul>
    </nav>
  </div>

  @if(isset($globalBreakingNews) && $globalBreakingNews->count() > 0)
  @php $bannerStory = $globalBreakingNews->first(); @endphp
  <div class="breaking-banner">
    <div class="breaking-label-container">
        <span class="breaking-pulse">🔴</span>
        <span class="breaking-label-new">BREAKING</span>
    </div>
    <div class="breaking-marquee">
        <div class="breaking-track">
            @foreach($globalBreakingNews as $news)
                <a href="{{ route('news.show', $news) }}" class="breaking-item">
                    <span class="breaking-title">{{ $news->title }}</span>
                    <span class="breaking-cta">Read More →</span>
                </a>
                <span class="breaking-separator">|</span>
            @endforeach
            <!-- Duplicate content for seamless infinite scroll -->
            @foreach($globalBreakingNews as $news)
                <a href="{{ route('news.show', $news) }}" class="breaking-item">
                    <span class="breaking-title">{{ $news->title }}</span>
                    <span class="breaking-cta">Read More →</span>
                </a>
                <span class="breaking-separator">|</span>
            @endforeach
        </div>
    </div>
  </div>
  @elseif(session('success'))
  <div class="breaking-success">
      <div class="success-banner">
          {{ session('success') }}
      </div>
  </div>
  @endif
</header>

@if(session('success') || session('error') || $errors->any())
  <div id="siteToast" class="site-toast {{ session('error') || $errors->any() ? 'site-toast-error' : 'site-toast-success' }}" role="status" aria-live="polite">
    <strong>{{ session('error') || $errors->any() ? 'Please check this' : 'Submitted' }}</strong>
    <span>
      @if(session('success'))
        {{ session('success') }}
      @elseif(session('error'))
        {{ session('error') }}
      @else
        {{ $errors->first() }}
      @endif
    </span>
  </div>
  <script>
    window.setTimeout(function () {
      const toast = document.getElementById('siteToast');
      if (toast) {
        toast.classList.add('site-toast-hide');
        window.setTimeout(function () {
          toast.remove();
        }, 300);
      }
    }, 5000);
  </script>
@endif

<main class="@yield('main_class', 'container')">
    @yield('content')
</main>

{{-- Footer Banner Ad --}}
@if(!empty($globalFooterAds))
<div class="ad-footer-zone" id="adFooter"
     data-ads="{{ json_encode($globalFooterAds) }}"
     data-img-class="ad-footer-img">
    <span class="ad-label-tag">Advertisement</span>
    <div class="ad-rotator-slot"></div>
    <button class="ad-close-btn" onclick="dismissAd('adFooter')" title="Close" aria-label="Close advertisement">✕</button>
</div>
@endif

<footer>
  <div class="subscribe-container" id="subscribeSection">
    <div id="subscribeFormWrapper">
      <h3 class="theme-strong-text">Subscribe for instant breaking news</h3>
      <p class="subscribe-subtitle">Join our growing community getting the latest global news in your inbox.</p>
      <form id="subscribeForm" action="{{ route('subscribe') }}" method="POST">
          @csrf
          <div class="subscribe-wrapper">
              <input type="email" id="subEmail" name="email" placeholder="Enter your email address" required>
              <button type="submit" id="subBtn">
                  <span class="btn-text">SUBSCRIBE</span>
                  <span class="btn-loader hidden">...</span>
              </button>
          </div>
      </form>
      <p class="sub-footer">No spam. Unsubscribe anytime.</p>
    </div>
    <div id="subMessage" class="subscribe-message hidden"></div>
  </div>
  <div class="footer-grid">
    <div class="footer-col footer-branding">
      <a href="{{ url('/') }}" class="footer-logo footer-logo-link">
          <span class="footer-logo-main">GLOBAL</span>
          <span class="footer-logo-highlight">BREAKINGS</span>
      </a>
      <p class="footer-description">
        Your trusted source for breaking news, in-depth analysis, and perspective from around the globe.
        Committed to truth and transparency.
      </p>
      <div class="footer-social-group">
        @include('partials.social-icons')
      </div>

    </div>

    <div class="footer-col">
        <h4>Browse Categories</h4>
        <div class="footer-links-grid">
            @if(isset($globalCategories))
                @foreach($globalCategories->where('slug', '!=', 'documentary') as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}">{{ $cat->name }}</a>
                @endforeach
            @else
                <p class="text-sm text-gray-500">Categories unavailable</p>
            @endif
        </div>
    </div>

    <div class="footer-col">
        <h4>Company & Support</h4>
        <a href="{{ route('about') }}">About Us</a>
        <a href="{{ route('contact') }}">Contact Us</a>
        <a href="{{ route('terms') }}">Terms & Conditions</a>
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <div class="contact-info">
            @if(isset($globalSettings['contact_email']))
            <a href="mailto:{{ $globalSettings['contact_email'] }}" class="contact-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>
                <span>{{ $globalSettings['contact_email'] }}</span>
            </a>
            @endif
            @if(isset($globalSettings['contact_phone']))
            <a href="tel:{{ $globalSettings['contact_phone'] }}" class="contact-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
                <span>{{ $globalSettings['contact_phone'] }}</span>
            </a>
            @endif
        </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>@Global Breakings. All rights reserved 2026</p>
  </div>
</footer>

<!-- Main Layout Scripts -->
<script src="{{ asset('js/main-layout.js') }}"></script>
<script src="{{ asset('js/auto-embed.js') }}"></script>
<script>
    function dismissAd(id) {
        const ad = document.getElementById(id);
        if (ad) {
            ad.style.overflow = 'hidden';
            ad.style.maxHeight = ad.scrollHeight + 'px';
            ad.offsetHeight;
            ad.classList.add('ad-dismissed');
            setTimeout(() => ad.remove(), 350);
        }
    }

    class AdRotator {
        constructor(zone) {
            this.zone = zone;
            try { this.ads = JSON.parse(zone.dataset.ads || '[]'); } catch(e) { this.ads = []; }
            this.slot = zone.querySelector('.ad-rotator-slot');
            this.imgClass  = zone.dataset.imgClass  || '';
            this.linkClass = zone.dataset.linkClass || '';
            this.index = 0;
            if (!this.slot || !this.ads.length) { zone.style.display = 'none'; return; }
            this.show(0);
            if (this.ads.length > 1) {
                setInterval(() => {
                    this.index = (this.index + 1) % this.ads.length;
                    this.rotate();
                }, 7000);
            }
        }
        show(i) {
            const ad = this.ads[i];
            const e = s => String(s || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            const img = `<img src="${e(ad.image_url)}" alt="${e(ad.title)}" class="${this.imgClass}" loading="lazy">`;
            const lc = this.linkClass ? ` class="${this.linkClass}"` : '';
            this.slot.innerHTML = ad.click_url
                ? `<a href="${e(ad.click_url)}" target="_blank" rel="noopener sponsored nofollow"${lc}>${img}</a>`
                : `<div${lc}>${img}</div>`;
            this.trackImpression(ad);
        }
        rotate() {
            this.slot.style.opacity = '0';
            setTimeout(() => {
                this.show(this.index);
                this.slot.style.opacity = '1';
            }, 200);
        }
        trackImpression(ad) {
            if (!ad.imp_url) return;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            fetch(ad.imp_url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                credentials: 'same-origin'
            }).catch(() => {});
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-ads]').forEach(zone => new AdRotator(zone));
    });
</script>
</body>
</html>
