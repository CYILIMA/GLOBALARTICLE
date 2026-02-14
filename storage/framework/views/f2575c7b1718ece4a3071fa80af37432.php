

<?php $__env->startSection('title', $documentary->title); ?>
<?php $__env->startSection('meta_description', $documentary->description); ?>

<?php $__env->startSection('content'); ?>
<article class="max-w-4xl mx-auto">
    <!-- Header -->
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-4 text-sm">
            <span class="text-purple-600 font-bold uppercase">DOCUMENTARY</span>
            <span class="text-gray-400">&bull;</span>
            <time datetime="<?php echo e($documentary->published_at); ?>" class="text-gray-500">
                <?php echo e($documentary->published_at->format('F d, Y')); ?>

            </time>
            <span class="text-gray-400">&bull;</span>
            <span class="text-gray-500">👁️ <?php echo e(number_format($documentary->views)); ?> views</span>
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
            <?php echo e($documentary->title); ?>

        </h1>

        <p class="text-xl text-gray-600 mb-6"><?php echo e($documentary->description); ?></p>

        <?php if($documentary->thumbnail): ?>
            <figure class="mb-8 rounded-xl overflow-hidden shadow-lg">
                <img src="<?php echo e(asset('storage/' . $documentary->thumbnail)); ?>" alt="<?php echo e($documentary->title); ?>" class="w-full h-auto object-cover max-h-[600px]">
            </figure>
        <?php endif; ?>

        <!-- Video Embed -->
        <?php if($documentary->video_url): ?>
            <div class="mb-8 rounded-xl overflow-hidden shadow-lg" style="position: relative; padding-bottom: 56.25%; height: 0;">
                <?php if(str_contains($documentary->video_url, 'youtube.com') || str_contains($documentary->video_url, 'youtu.be')): ?>
                    <?php
                        $videoId = null;
                        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $documentary->video_url, $matches)) {
                            $videoId = $matches[1];
                        } elseif (preg_match('/youtu\.be\/([^?]+)/', $documentary->video_url, $matches)) {
                            $videoId = $matches[1];
                        }
                    ?>
                    <?php if($videoId): ?>
                        <iframe src="https://www.youtube.com/embed/<?php echo e($videoId); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                    <?php endif; ?>
                <?php elseif(str_contains($documentary->video_url, 'vimeo.com')): ?>
                    <?php
                        preg_match('/vimeo\.com\/(\d+)/', $documentary->video_url, $matches);
                        $videoId = $matches[1] ?? null;
                    ?>
                    <?php if($videoId): ?>
                        <iframe src="https://player.vimeo.com/video/<?php echo e($videoId); ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></iframe>
                    <?php endif; ?>
                <?php else: ?>
                    <video controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                        <source src="<?php echo e($documentary->video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>

    <!-- Content -->
    <div class="prose prose-lg prose-blue max-w-none mb-12 text-gray-800 leading-relaxed">
        <?php echo $documentary->content; ?>

    </div>

    <!-- Related Documentaries -->
    <?php if($relatedDocumentaries->count() > 0): ?>
        <section class="bg-gray-50 rounded-xl p-8 mb-12">
            <h3 class="text-xl font-bold mb-6 border-b pb-2">More Documentaries</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__currentLoopData = $relatedDocumentaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('documentaries.show', $related)); ?>" class="group block">
                        <?php if($related->thumbnail): ?>
                            <div class="aspect-video rounded-lg overflow-hidden mb-3">
                                <img src="<?php echo e(asset('storage/' . $related->thumbnail)); ?>" alt="<?php echo e($related->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                        <?php else: ?>
                            <div class="aspect-video rounded-lg overflow-hidden mb-3 bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-4xl">
                                🎬
                            </div>
                        <?php endif; ?>
                        <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition leading-snug">
                            <?php echo e($related->title); ?>

                        </h4>
                        <span class="text-xs text-gray-500 mt-1 block"><?php echo e($related->published_at->diffForHumans()); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

</article>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\GLOBALARTICLE\resources\views/documentaries/show.blade.php ENDPATH**/ ?>