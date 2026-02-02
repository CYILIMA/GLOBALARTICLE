

<?php $__env->startSection('title', $news->meta_title ?? $news->title); ?>
<?php $__env->startSection('meta_description', $news->meta_description ?? Str::limit(strip_tags($news->content), 150)); ?>

<?php $__env->startSection('content'); ?>
<article class="article-container">
    <!-- Header -->
    <header class="article-header">
        <div class="article-meta">
            <a href="<?php echo e(route('home', ['category' => $news->category])); ?>" class="article-category-link">
                <?php echo e($news->category); ?>

            </a>
            <span class="article-meta-separator">&bull;</span>
            <time datetime="<?php echo e($news->published_at ?? $news->created_at); ?>" class="article-meta-time">
                <?php echo e(($news->published_at ?? $news->created_at)->format('F d, Y')); ?>

            </time>
        </div>

        <h1 class="article-title">
            <?php echo e($news->title); ?>

        </h1>

        <?php if($news->image): ?>
            <figure class="article-image-container">
                <img src="<?php echo e(asset('storage/' . $news->image)); ?>" alt="<?php echo e($news->title); ?>" class="article-image">
            </figure>
        <?php endif; ?>
    </header>

    <!-- Content -->
    <div class="article-content">
        <?php if($news->content_version === 'v2' && $news->content_blocks): ?>
            
            <?php $__currentLoopData = $news->content_blocks['blocks'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($block['type'] === 'paragraph'): ?>
                    <p class="<?php echo e($index === 0 ? 'article-intro' : ''); ?>">
                        <?php echo $block['data']['text'] ?? ''; ?>

                    </p>
                    
                <?php elseif($block['type'] === 'header'): ?>
                    <?php if($block['data']['level'] == 2): ?>
                        <h2 class="article-heading-2"><?php echo e($block['data']['text'] ?? ''); ?></h2>
                    <?php elseif($block['data']['level'] == 3): ?>
                        <h3 class="article-heading-3"><?php echo e($block['data']['text'] ?? ''); ?></h3>
                    <?php endif; ?>
                    
                <?php elseif($block['type'] === 'image'): ?>
                    <figure class="article-figure">
                        <img src="<?php echo e($block['data']['file']['url'] ?? ''); ?>" 
                             alt="<?php echo e($block['data']['caption'] ?? ''); ?>" 
                             class="article-figure-image">
                        <?php if(!empty($block['data']['caption'])): ?>
                            <figcaption class="article-figcaption">
                                <?php echo e($block['data']['caption']); ?>

                            </figcaption>
                        <?php endif; ?>
                    </figure>
                    
                <?php elseif($block['type'] === 'quote'): ?>
                    <blockquote class="article-quote">
                        <p><?php echo e($block['data']['text'] ?? ''); ?></p>
                        <?php if(!empty($block['data']['caption'])): ?>
                            <cite><?php echo e($block['data']['caption']); ?></cite>
                        <?php endif; ?>
                    </blockquote>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            
            <?php echo $news->content; ?>

        <?php endif; ?>
    </div>

    <!-- Comments Section -->
    <section class="article-section">
        <h3 class="article-section-title">Comments</h3>

        <!-- Comment Form -->
        <form action="<?php echo e(route('comments.store', $news)); ?>" method="POST" class="comment-form">
            <?php echo csrf_field(); ?>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Your Name</label>
                    <input type="text" name="author_name" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Your Email</label>
                    <input type="email" name="author_email" required class="form-input">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Comment</label>
                <textarea name="content" required class="form-textarea" placeholder="Share your thoughts..."></textarea>
            </div>
            <button type="submit" class="btn-primary">
                Post Comment
            </button>
        </form>

        <!-- Approved Comments -->
        <div class="comments-list">
            <?php $__empty_1 = true; $__currentLoopData = $news->comments()->approved()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="comment-card">
                    <div class="comment-header">
                        <div class="comment-avatar">
                            <?php echo e(substr($comment->author_name, 0, 1)); ?>

                        </div>
                        <div class="comment-info">
                            <h4 class="comment-author"><?php echo e($comment->author_name); ?></h4>
                            <span class="comment-time"><?php echo e($comment->created_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                    <p class="comment-content"><?php echo e($comment->content); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="empty-state">No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Related News -->
    <?php if($relatedNews->count() > 0): ?>
        <section class="article-section">
            <h3 class="article-section-title">More in <?php echo e($news->category); ?></h3>
            <div class="related-grid">
                <?php $__currentLoopData = $relatedNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('news.show', $related)); ?>" class="related-card">
                        <?php if($related->image): ?>
                            <div class="related-image-container">
                                <img src="<?php echo e(asset('storage/' . $related->image)); ?>" alt="<?php echo e($related->title); ?>" class="related-image">
                            </div>
                        <?php endif; ?>
                        <h4 class="related-title">
                            <?php echo e($related->title); ?>

                        </h4>
                        <span class="related-time"><?php echo e($related->created_at->diffForHumans()); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

</article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\GLOBALARTICLE\resources\views/news/show.blade.php ENDPATH**/ ?>