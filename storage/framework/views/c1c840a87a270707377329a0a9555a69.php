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
    <?php $__env->startSection('header', 'Manage Articles'); ?>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex gap-2">
                <a href="<?php echo e(route('dashboard', ['status' => 'all'])); ?>" class="px-3 py-1 rounded-full text-sm font-medium <?php echo e(request('status') == 'all' || !request('status') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">All</a>
                <a href="<?php echo e(route('dashboard', ['status' => 'published'])); ?>" class="px-3 py-1 rounded-full text-sm font-medium <?php echo e(request('status') == 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">Published</a>
                <a href="<?php echo e(route('dashboard', ['status' => 'draft'])); ?>" class="px-3 py-1 rounded-full text-sm font-medium <?php echo e(request('status') == 'draft' ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">Drafts</a>
                <a href="<?php echo e(route('dashboard', ['status' => 'scheduled'])); ?>" class="px-3 py-1 rounded-full text-sm font-medium <?php echo e(request('status') == 'scheduled' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">Scheduled</a>
            </div>

            <a href="<?php echo e(route('news.create')); ?>" class="text-sm font-bold py-2 px-4 rounded transition flex items-center gap-2 border" style="background: var(--button-bg); color: var(--button-text); border-color: var(--button-border);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Article
            </a>
        </div>

        <?php if($latestArticles->count() > 0): ?>
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Published</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php $__currentLoopData = $latestArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if($article->image): ?>
                                <img src="<?php echo e(asset('storage/' . $article->image)); ?>" class="w-10 h-10 rounded object-cover" alt="">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded bg-gray-200 dark:bg-gray-600"></div>
                            <?php endif; ?>
                            <div>
                                <span class="font-medium text-gray-900 dark:text-gray-100 block"><?php echo e(Str::limit($article->title, 40)); ?></span>
                                <?php if($article->breaking): ?>
                                    <span class="text-xs text-red-500 font-bold uppercase tracking-wider">Breaking</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($article->categoryData): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" style="background-color: <?php echo e($article->categoryData->color); ?>20; color: <?php echo e($article->categoryData->color); ?>">
                                <?php echo e($article->categoryData->name); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-xs text-gray-500"><?php echo e($article->category); ?></span>
                        <?php endif; ?>
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
                    <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                        <?php echo e($article->published_at ? $article->published_at->format('M d, Y H:i') : '-'); ?>

                    </td>
                    <td class="px-6 py-4 text-right">
                        <!-- Actions would go here -->
                        <a href="<?php echo e(route('news.edit', $article)); ?>" class="text-blue-600 hover:underline text-sm">Edit</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                No articles found matching this criteria.
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
<?php /**PATH D:\GLOBALARTICLE\resources\views/admin/news/index.blade.php ENDPATH**/ ?>