<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>Admin Dashboard | Global Breakings</title>
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
            --button-bg: #ffffff;
            --button-text: #111827;
            --button-border: #e5e7eb;
            --button-hover: #f3f4f6;
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --danger: #ef4444;
            --danger-bg-hover: #fef2f2;
            --link-primary: #3b82f6;
        }
        .dark {
            /* DARK MODE OVERRIDES */
            --sidebar-bg: #111827;
            --sidebar-hover: #1f2937;
            --header-bg: #111827;
            --header-border: #1f2937;
            --content-bg: #000000;
            --text-main: #f3f4f6;
            --text-sidebar: #9ca3af;
            --border-color: #1f2937;
            --button-bg: #000000;
            --button-text: #ffffff;
            --button-border: #374151;
            --button-hover: #1f2937;
            --primary: #60a5fa;
            --primary-hover: #3b82f6;
            --danger: #f87171;
            --danger-bg-hover: rgba(248, 113, 113, 0.1);
            --link-primary: #60a5fa;
        }

        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--text-main);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: background 0.3s, border 0.3s;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow-y: hidden;
            z-index: 50;
        }
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 30px; /* Better padding for the last item */
        }
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
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

        .logout-btn {
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            width: 100%;
            font-size: 0.875rem;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .logout-btn:hover {
            background: var(--danger-bg-hover);
            transform: translateX(4px);
        }

        /* Notification Drawer Styles */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    <!-- SIDEBAR -->
    <div class="admin-sidebar shadow-lg">
        <!-- Logo Area -->
        <div class="p-6 text-xl font-bold border-b" style="border-color: var(--border-color)">
            GLOBAL <span class="text-blue-600 dark:text-blue-400">{{ auth()->user()->role === 'admin' ? 'ADMIN' : 'ARTICLES' }}</span>
        </div>

        <div class="sidebar-nav">
            <nav class="mt-6">
                <!-- MAIN SECTION -->
                <div class="px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    Main
                </div>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>

                <!-- CONTENT SECTION -->
                <div class="mt-8 px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    Content
                </div>
                <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
                    📰 Articles
                </a>
                <a href="{{ route('news.create') }}" class="{{ request()->routeIs('news.create') ? 'active' : '' }}">
                    ✍️ New Article
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'editor', 'author']))
                <a href="{{ route('admin.documentaries.index') }}" class="{{ request()->routeIs('admin.documentaries.*') ? 'active' : '' }}">
                    🎬 Documentaries
                </a>
@endif
@if(in_array(auth()->user()->role, ['admin', 'editor']))
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    📁 Categories
                </a>
                <a href="{{ route('admin.comments.index') }}" class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    💬 Comments
                </a>
                @endif
                <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.index') ? 'active' : '' }}">
                    🖼️ Media Library
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.analytics.index') }}" class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                    📈 Analytics
                </a>
                @endif

                <!-- COMMUNITY SECTION -->
                @if(auth()->user()->role === 'admin')
                <div class="mt-8 px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    Community
                </div>
                <a href="{{ route('admin.subscribers') }}" class="{{ request()->routeIs('admin.subscribers') ? 'active' : '' }}">
                    👥 Subscribers
                </a>
                @endif

                <!-- ADMINISTRATION SECTION -->
                @if(auth()->user()->role === 'admin')
                <div class="mt-8 px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    Administration
                </div>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    👤 Team Management
                </a>
                @endif

                <!-- NOTIFICATIONS SECTION -->
                <div class="mt-8 px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    Activity
                </div>
                <div x-data="notificationSystem()" class="relative">
                    <button @click="open = true; loadNotifications()" class="w-full text-left px-5 py-3 flex items-center justify-between group hover:bg-gray-100 dark:hover:bg-gray-800 transition-all rounded-r-xl border-l-4 border-transparent hover:border-blue-500">
                        <div class="flex items-center gap-3">
                            <span class="w-5 h-5 flex items-center justify-center group-hover:scale-110 transition-transform text-gray-400 group-hover:text-blue-500">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </span>
                            <span class="font-bold text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100">Notifications</span>
                        </div>
                        <template x-if="unreadCount > 0">
                            <span x-text="unreadCount" class="px-2 py-0.5 bg-blue-500 text-white text-[10px] font-black rounded-full shadow-sm"></span>
                        </template>
                    </button>

                    <!-- Sidebar Drawer Backdrop -->
                    <div x-show="open"
                         x-transition:enter="transition opacity-100 ease-out duration-300"
                         x-transition:leave="transition opacity-0 ease-in duration-200"
                         @click="open = false"
                         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60]"
                         style="display: none;"></div>

                    <!-- Sidebar Drawer Panel -->
                    <div x-show="open"
                         x-transition:enter="transition transform ease-out duration-300"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition transform ease-in duration-200"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full"
                         class="fixed left-0 top-0 bottom-0 w-[420px] bg-white dark:bg-[#0f172a] shadow-[0_0_50px_rgba(0,0,0,0.2)] z-[70] flex flex-col border-r border-gray-100 dark:border-slate-800"
                         style="display: none;">

                         <!-- Header -->
                        <div class="p-8 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center bg-gray-50/30 dark:bg-slate-900/40 backdrop-blur-md">
                            <div class="flex items-center gap-4">
                                <h3 class="font-bold text-2xl tracking-tight text-gray-900 dark:text-slate-100">Notifications</h3>
                                <template x-if="unreadCount > 0">
                                    <span class="px-3 py-1 text-[11px] rounded-lg font-bold tracking-tight text-white bg-indigo-500 shadow-sm shadow-indigo-500/30" x-text="unreadCount + ' new'"></span>
                                </template>
                            </div>
                            <button @click="open = false" class="p-2.5 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl transition-all hover:rotate-90">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Quick Actions -->
                        <div class="px-8 py-3 bg-white dark:bg-slate-900 border-b border-gray-50 dark:border-slate-800/50 flex justify-between items-center">
                             <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">Recent Notifications</span>
                             <button @click="markAllAsRead()"
                                     class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 hover:text-indigo-600 transition-all flex items-center gap-2 group"
                                     :class="unreadCount === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                     :disabled="unreadCount === 0 || loading">
                                <svg x-show="!loading" class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <svg x-show="loading" class="animate-spin w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="loading ? 'Updating...' : 'Mark all as read'"></span>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 overflow-y-auto custom-scrollbar">
                            <template x-if="notifications.length === 0">
                                <div class="py-24 px-10 text-center flex flex-col items-center gap-5">
                                    <div class="w-24 h-24 rounded-[2rem] bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center text-5xl opacity-40">🌙</div>
                                    <div class="space-y-2">
                                        <p class="text-xl font-black text-gray-900 dark:text-gray-100 uppercase tracking-tighter">Quiet Day</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">No new notifications to show right now.</p>
                                    </div>
                                </div>
                            </template>

                            <div class="divide-y divide-gray-50 dark:divide-slate-800/40">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <div @click="handleNotificationClick(notif)"
                                         class="p-6 hover:bg-gray-50 dark:hover:bg-slate-800/30 cursor-pointer transition-all relative group"
                                         :class="notif.read_at ? 'opacity-50' : 'bg-indigo-50/20 dark:bg-indigo-500/5'">

                                        <!-- Unread Indicator Dot -->
                                        <div x-show="!notif.read_at"
                                             class="absolute left-4 top-8 w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]"></div>

                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-transform group-hover:scale-105"
                                                 :class="notif.read_at ? 'bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500' : 'bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400'"
                                                 x-html="getIcon(notif.type)">
                                            </div>
                                            <div class="flex-1 min-w-0 overflow-hidden">
                                                <div class="flex justify-between items-start gap-2 mb-0.5">
                                                    <h4 class="text-[12px] font-bold text-gray-900 dark:text-slate-200 leading-snug break-all" x-text="notif.title"></h4>
                                                    <span class="text-[9px] font-bold text-gray-400 dark:text-slate-500 whitespace-nowrap tabular-nums" x-text="formatTime(notif.created_at)"></span>
                                                </div>
                                                <p class="text-[11px] text-gray-500 dark:text-slate-400 font-medium leading-relaxed break-all whitespace-normal" x-text="notif.message"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="p-8 bg-gray-50/30 dark:bg-slate-900/40 backdrop-blur-md border-t border-gray-100 dark:border-slate-800">
                            <a href="{{ route('admin.notifications.index') }}"
                               class="flex items-center justify-center gap-3 py-3.5 rounded-2xl bg-white dark:bg-slate-900 shadow-sm hover:shadow-md border border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-widest transition-all hover:-translate-y-0.5 text-gray-600 dark:text-slate-300 group">
                                View full activity log
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- SECURITY SECTION -->
                @if(auth()->user()->role === 'admin')
                <div class="mt-8 px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    Security
                </div>
                <a href="{{ route('admin.security.login-history') }}" class="{{ request()->routeIs('admin.security.login-history') ? 'active' : '' }}">
                    🔒 Login History
                </a>
                <a href="{{ route('admin.security.activity-logs') }}" class="{{ request()->routeIs('admin.security.activity-logs') ? 'active' : '' }}">
                    📋 Activity Logs
                </a>
                @endif

                <!-- SYSTEM SECTION -->
                @if(in_array(auth()->user()->role, ['admin', 'editor']))
                <div class="mt-8 px-5 py-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">
                    System
                </div>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    ⚙️ Settings
                </a>
                @endif
                @endif

            </nav>
        </div>

        <!-- FOOTER / LOGOUT -->
        <div class="p-4 border-t" style="border-color: var(--border-color)">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4-4H7m6 4v1a3 3 0 01-6 0v-1m6 0H9"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
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
            <div id="success-flash" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 dark:bg-green-900 dark:text-green-100 rounded shadow-sm relative" role="alert">
                <p>{{ session('success') }}</p>
                <button id="success-flash-close" type="button" class="absolute top-3 right-3 text-green-700 dark:text-green-100 hover:text-green-900" aria-label="Dismiss notification">×</button>
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

        const successFlash = document.getElementById('success-flash');
        if (successFlash) {
            const closeBtn = document.getElementById('success-flash-close');
            const hideFlash = () => successFlash.remove();
            closeBtn?.addEventListener('click', hideFlash);
            setTimeout(hideFlash, 6000);
        }
    </script>
    @stack('scripts')

    <script>
        // Notification System (Drawer) logic
        function notificationSystem() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                loading: false,

                init() {
                    this.loadUnreadCount();
                    setInterval(() => this.loadUnreadCount(), 30000);
                },

                async loadUnreadCount() {
                    try {
                        const response = await fetch('{{ route("admin.notifications.unread-count") }}');
                        const data = await response.json();
                        this.unreadCount = data.count;
                    } catch (error) {
                        console.error('Failed to load unread count:', error);
                    }
                },

                async loadNotifications() {
                    try {
                        const response = await fetch('{{ route("admin.notifications.recent") }}?count=15');
                        this.notifications = await response.json();
                    } catch (error) {
                        console.error('Failed to load notifications:', error);
                    }
                },

                async markAsRead(id) {
                    try {
                        const response = await fetch(`{{ url('/notifications') }}/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            this.loadUnreadCount();
                            this.loadNotifications();
                        }
                    } catch (error) {
                        console.error('Failed to mark as read:', error);
                    }
                },

                handleNotificationClick(notif) {
                    if (!notif.read_at) {
                        this.markAsRead(notif.id);
                    }

                    if (notif.data && notif.data.link) {
                        window.location.href = notif.data.link;
                        return;
                    }

                    const fallbacks = {
                        'comment': '{{ route("admin.comments.index") }}',
                        'article_published': '/',
                        'article_pending': '{{ route("admin.news.index") }}'
                    };

                    if (fallbacks[notif.type]) {
                        window.location.href = fallbacks[notif.type];
                    }
                },

                async markAllAsRead() {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route("admin.notifications.read-all") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            this.unreadCount = 0;
                            await Promise.all([
                                this.loadNotifications(),
                                this.loadUnreadCount()
                            ]);
                        }
                    } catch (error) {
                        console.error('Failed to mark all as read:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                getIcon(type) {
                    const icons = {
                        'comment': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>`,
                        'article_published': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                        'article_pending': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                        'system_warning': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
                        'email_failed': `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
                    };
                    return icons[type] || `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>`;
                },

                formatTime(timestamp) {
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diff = Math.floor((now - date) / 1000);

                    if (diff < 60) return 'Just now';
                    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
                    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
                    if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
                    return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
                }
            }
        }
    </script>
</body>
</html>
