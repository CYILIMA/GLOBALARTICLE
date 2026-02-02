<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex, nofollow">

    <title>Admin Dashboard | Global Articles</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo1.png')); ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
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
            <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                Dashboard
            </a>
            <a href="<?php echo e(route('admin.news.index')); ?>" class="<?php echo e(request()->routeIs('admin.news.index') ? 'active' : ''); ?>">
                Articles
            </a>
            <a href="<?php echo e(route('news.create')); ?>" class="<?php echo e(request()->routeIs('news.create') ? 'active' : ''); ?>">
                + Add Article
            </a>
            <a href="<?php echo e(route('admin.subscribers')); ?>" class="<?php echo e(request()->routeIs('admin.subscribers') ? 'active' : ''); ?>">
                Subscribers
            </a>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>">
                Categories
            </a>
            <a href="<?php echo e(route('admin.media.index')); ?>" class="<?php echo e(request()->routeIs('admin.media.index') ? 'active' : ''); ?>">
                Media Library
            </a>
            
            <!-- Security Section -->
            <div class="mt-6 px-5 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Security
            </div>
            <a href="<?php echo e(route('admin.security.login-history')); ?>" class="<?php echo e(request()->routeIs('admin.security.login-history') ? 'active' : ''); ?>">
                🔒 Login History
            </a>
            <a href="<?php echo e(route('admin.security.activity-logs')); ?>" class="<?php echo e(request()->routeIs('admin.security.activity-logs') ? 'active' : ''); ?>">
                📋 Activity Logs
            </a>
            
            <!-- Settings -->
            <div class="mt-6 px-5 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                System
            </div>
            <a href="<?php echo e(route('admin.settings.index')); ?>" class="<?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                ⚙️ Settings
            </a>
            <a href="<?php echo e(route('admin.comments.index')); ?>" class="<?php echo e(request()->routeIs('admin.comments.*') ? 'active' : ''); ?>">
                💬 Comments
            </a>
            <a href="<?php echo e(route('admin.documentaries.index')); ?>" class="<?php echo e(request()->routeIs('admin.documentaries.*') ? 'active' : ''); ?>">
                🎬 Documentaries
            </a>
            
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-10 border-t pt-4" style="border-color: var(--border-color)">
                <?php echo csrf_field(); ?>
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
                <?php echo $__env->yieldContent('header'); ?>
            </h2>
        </div>
        
        <div class="flex items-center gap-6">
            <!-- Theme Toggle -->
            <button id="themeToggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition" title="Toggle Theme">
                <span id="themeIcon" class="text-xl">🌙</span>
            </button>

            <!-- Notification Bell -->
            <div x-data="notificationBell()" @click.away="open = false" class="relative">
                <button @click="open = !open; if(open) loadNotifications()" class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"></span>
                </button>

                <!-- Dropdown -->
                <div x-show="open" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50" style="display: none;">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-gray-900 dark:text-white">Notifications</h3>
                        <button @click="markAllAsRead()" class="text-xs text-blue-600 hover:underline">Mark all read</button>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <template x-if="notifications.length === 0">
                            <div class="p-4 text-center text-gray-500 dark:text-gray-400">No notifications</div>
                        </template>
                        <template x-for="notif in notifications" :key="notif.id">
                            <div @click="markAsRead(notif.id)" :class="{'bg-blue-50 dark:bg-blue-900/20': !notif.read_at}" class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <div class="flex items-start gap-3">
                                    <span x-text="getIcon(notif.type)" class="text-2xl"></span>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white" x-text="notif.title"></h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="notif.message"></p>
                                        <span class="text-xs text-gray-400 mt-1 block" x-text="formatTime(notif.created_at)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="<?php echo e(route('admin.notifications.index')); ?>" class="block text-center text-sm text-blue-600 hover:underline">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none hover:opacity-80 transition">
                    <span class="text-sm font-medium"><?php echo e(Auth::user()->name); ?></span>
                    <div class="h-8 w-8 rounded-full bg-blue-600 dark:bg-blue-500 text-white flex items-center justify-center font-bold shadow-sm">
                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

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
                        <p class="text-sm font-semibold truncate"><?php echo e(Auth::user()->email); ?></p>
                    </div>

                    <?php if(Route::has('profile.edit')): ?>
                    <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Profile
                    </a>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
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
        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 dark:bg-green-900 dark:text-green-100 rounded shadow-sm" role="alert">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php echo e($slot); ?>

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
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        // Notification Bell Alpine Component
        function notificationBell() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                
                init() {
                    this.loadUnreadCount();
                    // Poll for new notifications every 30 seconds
                    setInterval(() => this.loadUnreadCount(), 30000);
                },
                
                async loadUnreadCount() {
                    try {
                        const response = await fetch('<?php echo e(route("admin.notifications.unread-count")); ?>');
                        const data = await response.json();
                        this.unreadCount = data.count;
                    } catch (error) {
                        console.error('Failed to load unread count:', error);
                    }
                },
                
                async loadNotifications() {
                    try {
                        const response = await fetch('<?php echo e(route("admin.notifications.recent")); ?>');
                        this.notifications = await response.json();
                    } catch (error) {
                        console.error('Failed to load notifications:', error);
                    }
                },
                
                async markAsRead(id) {
                    try {
                        await fetch(`/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });
                        this.loadUnreadCount();
                        this.loadNotifications();
                    } catch (error) {
                        console.error('Failed to mark as read:', error);
                    }
                },
                
                async markAllAsRead() {
                    try {
                        await fetch('<?php echo e(route("admin.notifications.read-all")); ?>', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        });
                        this.unreadCount = 0;
                        this.loadNotifications();
                    } catch (error) {
                        console.error('Failed to mark all as read:', error);
                    }
                },
                
                getIcon(type) {
                    const icons = {
                        'comment': '💬',
                        'article_published': '📰',
                        'system_warning': '⚠️',
                        'email_failed': '📧'
                    };
                    return icons[type] || '🔔';
                },
                
                formatTime(timestamp) {
                    const date = new Date(timestamp);
                    const now = new Date();
                    const diff = Math.floor((now - date) / 1000);
                    
                    if (diff < 60) return 'Just now';
                    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
                    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
                    return `${Math.floor(diff / 86400)}d ago`;
                }
            }
        }
    </script>
</body>
</html>
<?php /**PATH D:\GLOBALARTICLE\resources\views/layouts/admin.blade.php ENDPATH**/ ?>