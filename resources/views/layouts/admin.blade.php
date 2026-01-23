<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>Admin Dashboard | Global Articles</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            /* LIGHT MODE DEFAULT */
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f3f4f6;
            --header-bg: #ffffff;
            --header-border: #e5e7eb;
            --content-bg: #f9fafb;
            --text-main: #111827;
            --text-sidebar: #4b5563;
            --border-color: #e5e7eb;
        }
        .dark {
            /* DARK MODE OVERRIDES */
            --sidebar-bg: #1e293b;
            --sidebar-hover: #0f172a;
            --header-bg: #1f2937;
            --header-border: #374151;
            --content-bg: #111827;
            --text-main: #f3f4f6;
            --text-sidebar: #cbd5e1;
            --border-color: #374151;
        }

        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--text-main);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: background 0.3s, border 0.3s;
            border-right: 1px solid var(--border-color);
        }
        .admin-sidebar a {
            color: var(--text-sidebar);
            padding: 12px 20px;
            display: block;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            font-weight: 500;
        }
        .admin-sidebar a:hover, .admin-sidebar a.active {
            background: var(--sidebar-hover);
            color: var(--text-main);
            border-left-color: #3b82f6;
        }
        .admin-header {
            margin-left: 260px;
            background: var(--header-bg);
            padding: 15px 30px;
            border-bottom: 1px solid var(--header-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-main);
            transition: background 0.3s, border 0.3s;
        }
        .admin-content {
            margin-left: 260px;
            padding: 30px;
            background: var(--content-bg);
            min-height: calc(100vh - 65px);
            color: var(--text-main);
            transition: background 0.3s;
        }
        
        /* Tailwind Fixes */
        .dark .bg-white { background-color: #1f2937 !important; color: #fff; }
        .dark .text-gray-900 { color: #f3f4f6 !important; }
        .dark .text-gray-800 { color: #f3f4f6 !important; }
        .dark .text-gray-600 { color: #9ca3af !important; }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    <!-- SIDEBAR -->
    <div class="admin-sidebar shadow-lg">
        <!-- Logo Area -->
        <div class="p-6 text-xl font-bold border-b" style="border-color: var(--border-color)">
            GLOBAL <span class="text-blue-600 dark:text-blue-400">ADMIN</span>
        </div>
        
        <nav class="mt-6">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
                Articles
            </a>
            <a href="{{ route('news.create') }}" class="{{ request()->routeIs('news.create') ? 'active' : '' }}">
                + Add Article
            </a>
            <a href="{{ route('admin.subscribers') }}" class="{{ request()->routeIs('admin.subscribers') ? 'active' : '' }}">
                Subscribers
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                Categories
            </a>
            <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.index') ? 'active' : '' }}">
                Media Library
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-10 border-t pt-4" style="border-color: var(--border-color)">
                @csrf
                <button type="submit" class="w-full text-left px-5 py-3 text-red-600 hover:text-red-700 hover:bg-gray-100 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-gray-800 transition font-medium">
                    Logout
                </button>
            </form>
        </nav>
    </div>

    <!-- MAIN CONTENT -->
    <div class="admin-header shadow-sm">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-semibold">
                @yield('header')
            </h2>
        </div>
        
        <div class="flex items-center gap-6">
            <!-- Theme Toggle -->
            <button id="themeToggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Toggle Theme">
                <span id="themeIcon" class="text-xl">🌙</span>
            </button>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none hover:opacity-80 transition">
                    <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                    <div class="h-8 w-8 rounded-full bg-blue-600 dark:bg-blue-500 text-white flex items-center justify-center font-bold shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-100 dark:border-gray-700"
                     style="display: none;">
                    
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Signed in as</p>
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->email }}</p>
                    </div>

                    @if (Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Profile
                    </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <main class="admin-content">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 dark:bg-green-900 dark:text-green-100 rounded shadow-sm" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
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
    </script>
    @stack('scripts')
</body>
</html>
