

<?php $__env->startSection('title', $documentary->title); ?>
<?php $__env->startSection('meta_description', $documentary->description); ?>

<?php $__env->startSection('content'); ?>
<!-- Main Content -->
<article class="documentary-main">
    <!-- Header -->
    <header class="mb-10">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-xs font-black uppercase tracking-widest rounded-full shadow-sm">
                Documentary
            </span>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <time datetime="<?php echo e($documentary->published_at); ?>" class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tighter">
                <?php echo e($documentary->published_at->format('F d, Y')); ?>

            </time>
            <div class="ml-auto flex items-center gap-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                    <?php echo e(number_format($documentary->views)); ?> Views
                </div>
                <?php if($documentary->author): ?>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400">
                    By <?php echo e($documentary->author->name); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl lg:text-7xl font-black text-gray-900 dark:text-white leading-[0.9] mb-8 tracking-tighter">
            <?php echo e($documentary->title); ?>

        </h1>

        <div class="flex flex-col md:flex-row md:items-center gap-6 mb-10 text-gray-600 dark:text-gray-400">
            <p class="text-xl font-medium leading-relaxed border-l-4 border-purple-500 pl-6 italic flex-1">
                <?php echo e($documentary->description); ?>

            </p>
        </div>

        <!-- Video Embed Container -->
        <?php if(trim($documentary->video_url)): ?>
            <div class="mb-12 rounded-[2rem] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] bg-black group relative aspect-video transform transition-all hover:scale-[1.01] duration-500">
                <?php
                    $videoId = null;
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $documentary->video_url, $matches)) {
                        $videoId = $matches[1];
                    }
                ?>

                <?php if($videoId): ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo e($videoId); ?>?rel=0&modestbranding=1&autoplay=0" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen 
                            class="absolute inset-0 w-full h-full transition-opacity duration-700 opacity-95 group-hover:opacity-100">
                    </iframe>
                <?php elseif(str_contains($documentary->video_url, 'vimeo.com')): ?>
                    <?php
                        preg_match('/vimeo\.com\/(\d+)/', $documentary->video_url, $matches);
                        $videoId = $matches[1] ?? null;
                    ?>
                    <?php if($videoId): ?>
                        <iframe src="https://player.vimeo.com/video/<?php echo e($videoId); ?>?color=a855f7&title=0&byline=0&portrait=0" 
                                frameborder="0" 
                                allow="autoplay; fullscreen; picture-in-picture" 
                                allowfullscreen 
                                class="absolute inset-0 w-full h-full transition-opacity duration-700 opacity-95 group-hover:opacity-100">
                        </iframe>
                    <?php endif; ?>
                <?php else: ?>
                    <video controls class="absolute inset-0 w-full h-full object-cover">
                        <source src="<?php echo e($documentary->video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            </div>
        <?php elseif($documentary->thumbnail): ?>
            <figure class="mb-12 rounded-[2rem] overflow-hidden shadow-2xl group relative">
                <img src="<?php echo e(asset('storage/' . $documentary->thumbnail)); ?>" alt="<?php echo e($documentary->title); ?>" 
                     class="w-full h-auto object-cover max-h-[700px] transition-transform duration-1000 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </figure>
        <?php endif; ?>
    </header>

    <!-- Content Body -->
    <div class="prose prose-2xl prose-indigo dark:prose-invert max-w-none mb-16 text-gray-800 dark:text-gray-300 leading-normal font-serif drop-cap">
        <?php echo $documentary->content; ?>

    </div>

    <!-- Related Documentaries (Bottom Grid) -->
    <?php if($relatedDocumentaries->count() > 0): ?>
        <section class="mt-20 border-t border-gray-100 dark:border-gray-800 pt-16">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">Next Up</h3>
                <div class="h-px flex-1 bg-gradient-to-r from-gray-100 to-transparent dark:from-gray-800 ml-8"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <?php $__currentLoopData = $relatedDocumentaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('documentaries.show', $related)); ?>" class="group block no-underline">
                        <div class="aspect-video rounded-3xl overflow-hidden mb-6 shadow-xl bg-gray-100 dark:bg-gray-800 relative transform transition-transform group-hover:-translate-y-2 duration-500">
                            <?php if($related->thumbnail): ?>
                                <img src="<?php echo e(asset('storage/' . $related->thumbnail)); ?>" alt="<?php echo e($related->title); ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                            <?php else: ?>
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-indigo-600/20"></div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/30 transform scale-50 group-hover:scale-100 transition-transform duration-500">
                                    <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                        <h4 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors leading-tight mb-3 tracking-tight">
                            <?php echo e($related->title); ?>

                        </h4>
                        <div class="flex items-center gap-3 text-[11px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                            <span><?php echo e($related->published_at->diffForHumans()); ?></span>
                            <span class="w-1 h-1 bg-gray-300 dark:bg-gray-700 rounded-full"></span>
                            <span><?php echo e(number_format($related->views)); ?> views</span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>
</article>

<!-- Sidebar -->
<aside class="sidebar-wrapper">
    <div class="sticky top-24 space-y-8">
        <!-- Recent Documentaries Widget -->
        <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-2xl rounded-[2rem] p-8 border border-white/50 dark:border-gray-800/50 shadow-2xl shadow-purple-500/5">
            <div class="flex items-center gap-4 mb-8">
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Latest Stories</h3>
            </div>

            <div class="space-y-8">
                <?php $__empty_1 = true; $__currentLoopData = $recentDocumentaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('documentaries.show', $recent)); ?>" class="group flex gap-5 items-start p-3 -mx-3 rounded-2xl hover:bg-white dark:hover:bg-gray-800/50 transition-all duration-300">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 shadow-lg bg-gray-100 dark:bg-gray-800 relative">
                            <?php if($recent->thumbnail): ?>
                                <img src="<?php echo e(asset('storage/' . $recent->thumbnail)); ?>" alt="<?php echo e($recent->title); ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <?php else: ?>
                                <div class="absolute inset-0 bg-purple-100 dark:bg-purple-900/20"></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <h4 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2 leading-tight mb-2 tracking-tight">
                                <?php echo e($recent->title); ?>

                            </h4>
                            <div class="flex items-center gap-2 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                <span><?php echo e($recent->published_at->diffForHumans()); ?></span>
                                <span class="w-1 h-1 bg-gray-200 dark:bg-gray-700 rounded-full"></span>
                                <span class="text-purple-600 dark:text-purple-400"><?php echo e(number_format($recent->views)); ?> views</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500 italic">No recent masterpieces found.</p>
                <?php endif; ?>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100 dark:border-gray-800">
                <a href="<?php echo e(url('/')); ?>" class="group/btn inline-flex items-center gap-3 text-xs font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest transition-all">
                    <span>Explore Feed</span>
                    <svg class="w-5 h-5 group-hover/btn:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>


<style>
    @keyframes pulse-purple {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.5); opacity: 0.5; }
    }
    .pulse-icon {
        animation: pulse-purple 2s infinite ease-in-out;
    }
    /* Hide default sidebar if it's being globally included by container class */
    .container > .sidebar {
        display: none !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\GLOBALARTICLE\resources\views/documentaries/show.blade.php ENDPATH**/ ?>