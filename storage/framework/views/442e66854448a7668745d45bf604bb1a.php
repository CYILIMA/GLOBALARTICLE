
<aside class="sidebar">
    <h3>Recent Headlines</h3>
    <ul id="latestNews">
        <?php $__empty_1 = true; $__currentLoopData = $sidebarLatest ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $latest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li>
                <a href="<?php echo e(route('news.show', $latest)); ?>">
                    <span class="headline-title"><?php echo e(Str::limit($latest->title, 70)); ?></span>
                    <span class="headline-time"><?php echo e($latest->created_at->diffForHumans()); ?></span>
                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
             <li style="color: var(--text-muted); font-style: italic; padding: 10px 0;">No latest articles found.</li>
        <?php endif; ?>
    </ul>
</aside>
<?php /**PATH D:\GLOBALARTICLE\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>