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
  <section id="documentaryArea" style="margin-top: 40px; grid-column: 1 / -1;">
      <h2>Featured Documentaries</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
          <?php $__currentLoopData = $documentaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $documentary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('documentaries.show', $documentary)); ?>" class="news-card" style="text-decoration: none; transition: transform 0.3s;">
              <?php if($documentary->thumbnail): ?>
                  <img src="<?php echo e(asset('storage/' . $documentary->thumbnail)); ?>" alt="<?php echo e($documentary->title); ?>" loading="lazy" style="border-radius: 8px;">
              <?php else: ?>
                  <img src="<?php echo e(asset('images/default-thumb.jpg')); ?>" alt="<?php echo e($documentary->title); ?>" loading="lazy" style="border-radius: 8px;">
              <?php endif; ?>
              <div class="news-content">
                  <small style="color: #667eea; font-weight: bold;">DOCUMENTARY</small>
                  <h3><?php echo e($documentary->title); ?></h3>
                  <p><?php echo e(Str::limit($documentary->description, 120)); ?></p>
                  <div style="margin-top: 10px; font-size: 13px; color: #555; display: flex; justify-content: space-between;">
                      <span><?php echo e($documentary->published_at->diffForHumans()); ?></span>
                      <span>👁️ <?php echo e(number_format($documentary->views)); ?> views</span>
                  </div>
              </div>
          </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
  </section>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\GLOBALARTICLE\resources\views/home.blade.php ENDPATH**/ ?>