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
        <div class="p-6 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color); border-left-color: #3b82f6;">
            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-sidebar)">Total Articles</div>
            <div class="mt-2 text-3xl font-bold" style="color: var(--text-main)"><?php echo e($totalNews); ?></div>
            <div class="mt-1 text-xs text-green-600 dark:text-green-400 flex items-center">
                <span class="mr-1">↑</span> <?php echo e($publishedCount); ?> Published
            </div>
        </div>

        <?php if(auth()->user()->role === 'admin'): ?>
        <!-- Total Subscribers -->
        <div class="p-6 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color); border-left-color: #10b981;">
            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-sidebar)">Total Subscribers</div>
            <div class="mt-2 text-3xl font-bold" style="color: var(--text-main)"><?php echo e($totalSubscribers); ?></div>
        </div>
        <?php endif; ?>

        <!-- Breaking News -->
        <div class="p-6 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color); border-left-color: #ef4444;">
            <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--text-sidebar)">Breaking News</div>
            <div class="mt-2 text-3xl font-bold" style="color: var(--text-main)"><?php echo e($breakingCount); ?></div>
            <div class="text-xs text-red-500 mt-1">Currently Active</div>
        </div>
    </div>

    <!-- CONTENT INSIGHTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="p-4 rounded-lg shadow-sm flex items-center justify-between border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div>
                <div class="text-xs uppercase font-bold" style="color: var(--text-sidebar)">Drafts</div>
                <div class="text-2xl font-bold" style="color: var(--text-main)"><?php echo e($draftsCount); ?></div>
            </div>
            <div class="p-2 rounded" style="background: var(--sidebar-hover); color: var(--text-sidebar)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>
        <div class="p-4 rounded-lg shadow-sm flex items-center justify-between border transition-all" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div>
                <div class="text-xs uppercase font-bold" style="color: var(--text-sidebar)">Scheduled</div>
                <div class="text-2xl font-bold" style="color: var(--text-main)"><?php echo e($scheduledCount); ?></div>
            </div>
            <div class="p-2 rounded bg-yellow-100/20 text-yellow-600 dark:text-yellow-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex flex-wrap gap-4 mb-8">
        <a href="<?php echo e(route('news.create')); ?>" class="font-bold py-3 px-6 rounded shadow flex items-center gap-2 border transition-all hover:opacity-80" style="background: var(--sidebar-bg); color: var(--text-main); border-color: var(--border-color);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--text-sidebar)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Write New Article
        </a>
        <?php if(auth()->user()->role === 'admin'): ?>
        <a href="<?php echo e(route('admin.subscribers')); ?>" class="font-bold py-3 px-6 rounded shadow flex items-center gap-2 border transition-all hover:opacity-80" style="background: var(--sidebar-bg); color: var(--text-main); border-color: var(--border-color);">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" style="color: var(--text-sidebar)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Manage Subscribers
        </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- LATEST ARTICLES -->
        <div class="rounded-lg shadow-sm overflow-hidden border transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="px-6 py-4 border-b flex justify-between items-center" style="background: var(--sidebar-hover); border-color: var(--border-color)">
                <h3 class="font-bold" style="color: var(--text-main)">Latest Articles</h3>
                <a href="<?php echo e(route('admin.news.index')); ?>" class="text-xs font-bold hover:opacity-80 transition-all uppercase tracking-widest" style="color: var(--primary)">View All</a>
            </div>
            <?php if($latestArticles->count() > 0): ?>
                <table class="w-full text-left">
                    <tbody class="divide-y" style="border-color: var(--border-color)">
                        <?php $__currentLoopData = $latestArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="transition-colors hover:opacity-80" style="background: transparent; border-color: var(--border-color)">
                            <td class="px-6 py-4">
                                <span class="font-medium block" style="color: var(--text-main)"><?php echo e(Str::limit($article->title, 40)); ?></span>
                                <span class="text-xs" style="color: var(--text-sidebar)"><?php echo e($article->category); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $article->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xs" style="color: var(--text-sidebar)"><?php echo e($article->created_at->format('M d')); ?></span>
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

        <?php if(auth()->user()->role === 'admin'): ?>
        <!-- RECENT SUBSCRIBERS -->
        <div class="rounded-lg shadow-sm overflow-hidden border transition-colors" style="background: var(--sidebar-bg); border-color: var(--border-color)">
            <div class="px-6 py-4 border-b flex justify-between items-center" style="background: var(--sidebar-hover); border-color: var(--border-color)">
                <h3 class="font-bold" style="color: var(--text-main)">New Subscribers</h3>
                <a href="<?php echo e(route('admin.subscribers')); ?>" class="text-xs font-bold hover:opacity-80 transition-all uppercase tracking-widest" style="color: var(--primary)">Manage</a>
            </div>
            <?php if($recentSubscribers->count() > 0): ?>
                <table class="w-full text-left">
                    <tbody class="divide-y" style="border-color: var(--border-color)">
                        <?php $__currentLoopData = $recentSubscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="transition-colors hover:opacity-80" style="background: transparent; border-color: var(--border-color)">
                            <td class="px-6 py-4">
                                <span class="block font-medium" style="color: var(--text-main)"><?php echo e($sub->email); ?></span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if (isset($component)) { $__componentOriginal8c81617a70e11bcf247c4db924ab1b62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status-badge','data' => ['status' => $sub->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sub->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $attributes = $__attributesOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__attributesOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62)): ?>
<?php $component = $__componentOriginal8c81617a70e11bcf247c4db924ab1b62; ?>
<?php unset($__componentOriginal8c81617a70e11bcf247c4db924ab1b62); ?>
<?php endif; ?>
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
        <?php endif; ?>
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