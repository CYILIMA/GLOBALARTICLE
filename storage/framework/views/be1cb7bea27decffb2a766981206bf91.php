<?php if (isset($component)) { $__componentOriginal91fdd17964e43374ae18c674f95cdaa3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3 = $attributes; } ?>
<?php $component = App\View\Components\AdminLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AdminLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('header', 'Dashboard Overview'); ?>

    <!-- STATS ROW -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Articles -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Articles</div>
            <div class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($totalNews); ?></div>
            <div class="mt-1 text-xs text-green-600 dark:text-green-400 flex items-center">
                <span class="mr-1">↑</span> <?php echo e($publishedCount); ?> Published
            </div>
        </div>

        <!-- Total Subscribers -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Total Subscribers</div>
            <div class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($totalSubscribers); ?></div>
        </div>

        <!-- Breaking News -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">Breaking News</div>
            <div class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($breakingCount); ?></div>
            <div class="text-xs text-red-500 mt-1">Currently Active</div>
        </div>
    </div>

    <!-- CONTENT INSIGHTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Drafts</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($draftsCount); ?></div>
            </div>
            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded text-gray-500 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold">Scheduled</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo e($scheduledCount); ?></div>
            </div>
            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded text-yellow-600 dark:text-yellow-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex flex-wrap gap-4 mb-8">
        <a href="<?php echo e(route('news.create')); ?>" class="bg-white hover:bg-gray-50 text-blue-600 border border-blue-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-400 dark:border-gray-600 font-bold py-3 px-6 rounded shadow flex items-center gap-2 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Write New Article
        </a>
        <a href="<?php echo e(route('admin.subscribers')); ?>" class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 font-bold py-3 px-6 rounded shadow flex items-center gap-2 transition">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Manage Subscribers
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- LATEST ARTICLES -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Latest Articles</h3>
                <a href="#" class="text-xs text-blue-500 hover:underline">View All</a>
            </div>
            <?php if($latestArticles->count() > 0): ?>
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $latestArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 dark:text-gray-100 block"><?php echo e(Str::limit($article->title, 40)); ?></span>
                                <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($article->category); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusClasses = match($article->status) {
                                        'published' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'scheduled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'archived' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-semibold <?php echo e($statusClasses); ?>">
                                    <?php echo e(ucfirst($article->status ?? 'Draft')); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs text-gray-400 dark:text-gray-500"><?php echo e($article->created_at->format('M d')); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-6 text-center text-gray-500 text-sm">
                    No articles published yet.
                </div>
            <?php endif; ?>
        </div>

        <!-- RECENT SUBSCRIBERS -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">New Subscribers</h3>
                <a href="<?php echo e(route('admin.subscribers')); ?>" class="text-xs text-blue-500 hover:underline">Manage</a>
            </div>
            <?php if($recentSubscribers->count() > 0): ?>
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $recentSubscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-gray-800 block"><?php echo e($sub->email); ?></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-1 rounded text-xs <?php echo e($sub->status=='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e(ucfirst($sub->status)); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-6 text-center text-gray-500 text-sm">
                    No subscribers yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $attributes = $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $component = $__componentOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php /**PATH D:\GLOBALARTICLE\resources\views/dashboard.blade.php ENDPATH**/ ?>