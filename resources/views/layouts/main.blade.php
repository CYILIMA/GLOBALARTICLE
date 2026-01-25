<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>GLOBAL ARTICLES | Breaking & Latest News</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- CSS FILE -->
  <!-- CSS FILE -->
  <link rel="stylesheet" href="{{ asset('css/CSSONLY.css') }}?v={{ time() }}">
</head>
<body>

<header>
  <div class="top-bar">
    <div class="logo" onclick="window.location='{{ url('/') }}'">GLOBAL <span>ARTICLES</span></div>
    
    <nav class="desktop-nav">
      <ul>
        <li><a href="{{ url('/') }}" class="{{ request()->routeIs('home') && !request()->has('category') ? 'active' : '' }}">HOME</a></li>
        <li><a href="{{ url('/?category=politics') }}" class="{{ request('category') == 'politics' ? 'active' : '' }}">POLITICS</a></li>
        <li><a href="{{ url('/?category=technology') }}" class="{{ request('category') == 'technology' ? 'active' : '' }}">TECHNOLOGY</a></li>
        <li><a href="{{ url('/?category=sports') }}" class="{{ request('category') == 'sports' ? 'active' : '' }}">SPORTS</a></li>
        <li><a href="{{ url('/?category=entertainment') }}" class="{{ request('category') == 'entertainment' ? 'active' : '' }}">ENTERTAINMENT</a></li>
        <li><a href="{{ url('/?category=economy') }}" class="{{ request('category') == 'economy' ? 'active' : '' }}">ECONOMY</a></li>
        <li><a href="{{ url('/?category=health') }}" class="{{ request('category') == 'health' ? 'active' : '' }}">HEALTH</a></li>
        <li><a href="{{ url('/?category=religion') }}" class="{{ request('category') == 'religion' ? 'active' : '' }}">RELIGION</a></li>
      </ul>
    </nav>

    <div class="header-right" style="display: flex; align-items: center; gap: 15px; margin-left: 20px;">
        <!-- Theme Toggle -->
        <button id="themeToggle" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'" style="background: none; border: none; cursor: pointer; font-size: 20px; padding: 5px; color: var(--text-main); display: flex; align-items: center; justify-content: center; transition: transform 0.3s;" title="Toggle Theme">
            <span id="themeIcon">🌙</span>
        </button>

        <!-- Hamburger -->
        <div class="hamburger" onclick="toggleMenu()">☰</div>
    </div>
     <!-- Mobile Nav -->
    <nav id="mobileNav">
      <div class="close-btn" onclick="toggleMenu()">✕</div>
      <ul>
        <li><a href="{{ url('/') }}" class="{{ request()->routeIs('home') && !request()->has('category') ? 'active' : '' }}">HOME</a></li>
        <li><a href="{{ url('/?category=politics') }}" class="{{ request('category') == 'politics' ? 'active' : '' }}">POLITICS</a></li>
        <li><a href="{{ url('/?category=technology') }}" class="{{ request('category') == 'technology' ? 'active' : '' }}">TECHNOLOGY</a></li>
        <li><a href="{{ url('/?category=sports') }}" class="{{ request('category') == 'sports' ? 'active' : '' }}">SPORTS</a></li>
        <li><a href="{{ url('/?category=entertainment') }}" class="{{ request('category') == 'entertainment' ? 'active' : '' }}">ENTERTAINMENT</a></li>
        <li><a href="{{ url('/?category=economy') }}" class="{{ request('category') == 'economy' ? 'active' : '' }}">ECONOMY</a></li>
        <li><a href="{{ url('/?category=health') }}" class="{{ request('category') == 'health' ? 'active' : '' }}">HEALTH</a></li>
        <li><a href="{{ url('/?category=religion') }}" class="{{ request('category') == 'religion' ? 'active' : '' }}">RELIGION</a></li>
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
      <div style="background: green; color: white; padding: 10px; text-align: center; font-weight: bold; width: 100%;">
          {{ session('success') }}
      </div>
  </div>
  @endif
</header>

<main class="container">
    @yield('content')
</main>

<footer>
  <div class="subscribe-container" id="subscribeSection">
    <h3>Subscribe for instant breaking news</h3>
    <form id="subscribeForm" action="{{ route('subscribe') }}" method="POST">
        @csrf
        <div class="subscribe-wrapper">
            <input type="email" id="subEmail" name="email" placeholder="Enter your email address" required>
            <button type="submit" id="subBtn">
                <span class="btn-text">SUBSCRIBE</span>
                <span class="btn-loader" style="display:none;">...</span>
            </button>
        </div>
        <div id="subMessage" class="subscribe-message" style="display:none;"></div>
    </form>
    <p class="sub-footer">No spam. Unsubscribe anytime.</p>
  </div>
  <div class="footer-grid">
    <div class="footer-col">
      <div class="logo" style="font-size: 24px; margin-bottom: 20px;">GLOBAL <span>ARTICLES</span></div>
      <p style="color: #aaa; margin-bottom: 20px;">
        Your trusted source for breaking news, in-depth analysis, and perspective from around the globe.
        Committed to truth and transparency.
      </p>
      <div class="socials">
        <!-- Facebook -->
        <a href="https://facebook.com" target="_blank" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
            </svg>
        </a>
        <!-- X (Twitter) -->
        <a href="https://x.com" target="_blank" aria-label="X">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
            </svg>
        </a>
        <!-- Instagram -->
        <a href="https://instagram.com" target="_blank" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
            </svg>
        </a>
        <!-- YouTube -->
        <a href="https://youtube.com" target="_blank" aria-label="YouTube">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"/>
            </svg>
        </a>
      </div>
    </div>

    <div class="footer-col">
        <h4>Browse Categories</h4>
        <div class="footer-links-grid">
            <a href="{{ url('/?category=politics') }}">Politics</a>
            <a href="{{ url('/?category=technology') }}">Technology</a>
            <a href="{{ url('/?category=sports') }}">Sports</a>
            <a href="{{ url('/?category=entertainment') }}">Entertainment</a>
            <a href="{{ url('/?category=economy') }}">Economy</a>
            <a href="{{ url('/?category=health') }}">Health</a>
            <a href="{{ url('/?category=religion') }}">Religion</a>
        </div>
    </div>

    <div class="footer-col">
        <h4>Company & Support</h4>
        <a href="#">About Us</a>
        <a href="#">Terms & Conditions</a>
        <a href="#">Privacy Policy</a>
        <div class="contact-info">
            <a href="mailto:contact@globalarticles.com">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>
                contact@globalarticles.com
            </a>
            <a href="tel:+250796306573">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
                +250 796 306 573
            </a>
        </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container" style="text-align: center;">
      <p>&copy; 2026 Global Articles. Demo project.</p>
    </div>
  </div>
</footer>

<script>
    function toggleMenu() {
        const nav = document.getElementById('mobileNav');
        nav.classList.toggle('active');
    }

    // Theme Toggle Logic
    const toggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        themeIcon.textContent = '☀️';
    } else {
        html.classList.remove('dark');
        themeIcon.textContent = '🌙';
    }

    toggleBtn.addEventListener('click', () => {
        html.classList.toggle('dark');
        if (html.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            themeIcon.textContent = '☀️';
        } else {
            localStorage.setItem('theme', 'light');
            themeIcon.textContent = '🌙';
        }
    });
    
    // Subscription Form Handler
    document.getElementById('subscribeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = document.getElementById('subBtn');
        const btnText = btn.querySelector('.btn-text');
        const btnLoader = btn.querySelector('.btn-loader');
        const msgDiv = document.getElementById('subMessage');
        const emailInput = document.getElementById('subEmail');
        
        // Reset state
        msgDiv.style.display = 'none';
        msgDiv.className = 'subscribe-message';
        btn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline-block';
        
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            });
            
            const data = await response.json();
            
            msgDiv.style.display = 'block';
            if (response.ok) {
                msgDiv.textContent = data.message || 'Subscribed successfully!';
                msgDiv.classList.add('msg-success');
                form.reset();
            } else {
                msgDiv.textContent = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Subscription failed.');
                msgDiv.classList.add('msg-error');
            }
        } catch (error) {
            msgDiv.style.display = 'block';
            msgDiv.textContent = 'Something went wrong. Please try again.';
            msgDiv.classList.add('msg-error');
        } finally {
            btn.disabled = false;
            btnText.style.display = 'inline-block';
            btnLoader.style.display = 'none';
        }
    });
</script>
</body>
</html>
