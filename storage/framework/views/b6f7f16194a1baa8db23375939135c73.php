<?php $__env->startSection('content'); ?>
  <section id="newsArea">
      <?php if(isset($category)): ?>
          <h2>Category: <?php echo e(ucfirst($category)); ?></h2>
      <?php else: ?>
          <h2>Latest News</h2>
      <?php endif; ?>

      <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="news-card">
            <?php if($item->image): ?>
                <a href="<?php echo e(route('news.show', $item)); ?>">
                    <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="<?php echo e($item->title); ?>" loading="lazy">
                </a>
            <?php endif; ?>
            <div class="news-content">
                <a href="<?php echo e(route('home', ['category' => $item->category])); ?>" class="category-link"><small><?php echo e($item->category); ?></small></a>
                <h3><a href="<?php echo e(route('news.show', $item)); ?>"><?php echo e($item->title); ?></a></h3>
                <p><?php echo e(Str::limit(strip_tags($item->content), 150)); ?></p>
                <div style="margin-top: 10px; font-size: 13px; color: #555;">
                    By <?php echo e($item->author->name ?? 'Global Articles Editor'); ?> &bull; <?php echo e($item->created_at->diffForHumans()); ?>

                </div>
            </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="news-card" style="padding: 40px; text-align: center;">
            <h3>No News Yet</h3>
            <p>Check back later or subscribe for updates.</p>
        </div>
      <?php endif; ?>
  </section>

  <!-- SIDEBAR -->
  <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- DOCUMENTARY SECTION -->
  <?php if($documentaries->count() > 0): ?>
  <section id="documentaryArea" class="mt-20 pt-16 border-t border-gray-100 dark:border-gray-800" style="grid-column: 1 / -1;">
      <div class="flex items-center justify-between mb-10">
          <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase">Documentaries</h2>
          <div class="flex-1 h-px bg-gray-100 dark:bg-gray-800 mx-8"></div>
          <span class="px-4 py-1.5 bg-purple-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-purple-500/30">
              New Releases
          </span>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <?php $__currentLoopData = $documentaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $documentary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('documentaries.show', $documentary)); ?>" class="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-800/50">
              <div class="aspect-video relative overflow-hidden bg-gray-100 dark:bg-gray-800">
                  <?php if($documentary->thumbnail): ?>
                      <img src="<?php echo e(asset('storage/' . $documentary->thumbnail)); ?>" alt="<?php echo e($documentary->title); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                  <?php else: ?>
                      <div class="absolute inset-0 bg-purple-600/5"></div>
                  <?php endif; ?>
                  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
                  
                  <!-- Pulse Indicator -->
                  <div class="absolute top-4 left-4">
                      <span class="flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                      </span>
                  </div>
              </div>

              <div class="p-6">
                  <div class="flex items-center justify-between mb-3">
                      <span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-[0.2em]">Documentary</span>
                      <?php if($documentary->author): ?>
                      <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 dark:text-gray-400">
                          By <?php echo e($documentary->author->name); ?>

                      </div>
                      <?php endif; ?>
                  </div>
                  <h3 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-2 leading-tight mb-4 tracking-tight">
                      <?php echo e($documentary->title); ?>

                  </h3>
                  <div class="flex items-center justify-between mt-6 pt-5 border-t border-gray-50 dark:border-gray-800/80">
                      <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest"><?php echo e($documentary->published_at->diffForHumans()); ?></span>
                      <div class="flex items-center gap-2 text-[10px] font-black text-gray-500 dark:text-gray-400">
                          <div class="flex items-center gap-1.5">
                              <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                              <?php echo e(number_format($documentary->views)); ?> VIEWS
                          </div>
                      </div>
                  </div>
              </div>
          </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
  </section>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\GLOBALARTICLE\resources\views/home.blade.php ENDPATH**/ ?>