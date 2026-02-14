<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>GLOBAL ARTICLES | Breaking & Latest News</title>
  <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo1.png')); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- CSS FILE -->
  <link rel="stylesheet" href="<?php echo e(asset('css/CSSONLY.css')); ?>?v=<?php echo e(time()); ?>">
</head>
<body>

<header>
  <div class="top-bar">
    <div class="logo" onclick="window.location='<?php echo e(url('/')); ?>'">GLOBAL <span>ARTICLES</span></div>
    
    <nav class="desktop-nav">
      <ul>
        <li><a href="<?php echo e(url('/')); ?>" class="<?php echo e(request()->routeIs('home') && !request()->has('category') ? 'active' : ''); ?>">HOME</a></li>
        <?php if(isset($globalCategories)): ?>
            <?php $__currentLoopData = $globalCategories->take(7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(url('/?category=' . $cat->slug)); ?>" 
                       class="<?php echo e(request('category') == $cat->slug ? 'active' : ''); ?>">
                       <?php echo e(strtoupper($cat->name)); ?>

                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($globalCategories->count() > 7): ?>
                <li class="nav-dropdown">
                    <a href="#" class="nav-dropdown-trigger">MORE ▾</a>
                    <div class="nav-dropdown-content">
                        <?php $__currentLoopData = $globalCategories->skip(7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(url('/?category=' . $cat->slug)); ?>"
                               class="<?php echo e(request('category') == $cat->slug ? 'text-blue-600' : ''); ?>">
                                <?php echo e($cat->name); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="header-right">
        <!-- Theme Toggle -->
        <button id="themeToggle" class="theme-toggle-btn" title="Toggle Theme">
            <span id="themeIcon">🌙</span>
        </button>

        <!-- Hamburger -->
        <div class="hamburger" onclick="toggleMenu()">☰</div>
    </div>
     <!-- Mobile Nav -->
    <nav id="mobileNav">
      <div class="close-btn" onclick="toggleMenu()">✕</div>
      <ul>
        <li><a href="<?php echo e(url('/')); ?>" class="<?php echo e(request()->routeIs('home') && !request()->has('category') ? 'active' : ''); ?>">HOME</a></li>
        <?php if(isset($globalCategories)): ?>
            <?php $__currentLoopData = $globalCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(url('/?category=' . $cat->slug)); ?>" 
                       class="<?php echo e(request('category') == $cat->slug ? 'active' : ''); ?>">
                       <?php echo e(strtoupper($cat->name)); ?>

                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </ul>
    </nav>
  </div>

  <?php if(isset($globalBreakingNews) && $globalBreakingNews->count() > 0): ?>
  <?php $bannerStory = $globalBreakingNews->first(); ?>
  <div class="breaking-banner">
    <div class="breaking-label-container">
        <span class="breaking-pulse">🔴</span>
        <span class="breaking-label-new">BREAKING</span>
    </div>
    <div class="breaking-marquee">
        <div class="breaking-track">
            <?php $__currentLoopData = $globalBreakingNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('news.show', $news)); ?>" class="breaking-item">
                    <span class="breaking-title"><?php echo e($news->title); ?></span>
                    <span class="breaking-cta">Read More →</span>
                </a>
                <span class="breaking-separator">|</span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <!-- Duplicate content for seamless infinite scroll -->
            <?php $__currentLoopData = $globalBreakingNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('news.show', $news)); ?>" class="breaking-item">
                    <span class="breaking-title"><?php echo e($news->title); ?></span>
                    <span class="breaking-cta">Read More →</span>
                </a>
                <span class="breaking-separator">|</span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
  </div>
  <?php elseif(session('success')): ?>
  <div class="breaking-success">
      <div class="success-banner">
          <?php echo e(session('success')); ?>

      </div>
  </div>
  <?php endif; ?>
</header>

<main class="container">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer>
  <div class="subscribe-container" id="subscribeSection">
    <h3>Subscribe for instant breaking news</h3>
    <form id="subscribeForm" action="<?php echo e(route('subscribe')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="subscribe-wrapper">
            <input type="email" id="subEmail" name="email" placeholder="Enter your email address" required>
            <button type="submit" id="subBtn">
                <span class="btn-text">SUBSCRIBE</span>
                <span class="btn-loader hidden">...</span>
            </button>
        </div>
        <div id="subMessage" class="subscribe-message hidden"></div>
    </form>
    <p class="sub-footer">No spam. Unsubscribe anytime.</p>
  </div>
  <div class="footer-grid">
    <div class="footer-col">
      <div class="logo footer-logo">GLOBAL <span>ARTICLES</span></div>
      <p class="footer-description">
        Your trusted source for breaking news, in-depth analysis, and perspective from around the globe.
        Committed to truth and transparency.
      </p>
      <?php echo $__env->make('partials.social-icons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    </div>

    <div class="footer-col">
        <h4>Browse Categories</h4>
        <div class="footer-links-grid">
            <?php if(isset($globalCategories)): ?>
                <?php $__currentLoopData = $globalCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(url('/?category=' . $cat->slug)); ?>"><?php echo e($cat->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <p class="text-sm text-gray-500">Categories unavailable</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer-col">
        <h4>Company & Support</h4>
        <a href="#">About Us</a>
        <a href="#">Terms & Conditions</a>
        <a href="#">Privacy Policy</a>
        <div class="contact-info">
            <?php if(isset($globalSettings['contact_email'])): ?>
            <a href="mailto:<?php echo e($globalSettings['contact_email']); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>
                <?php echo e($globalSettings['contact_email']); ?>

            </a>
            <?php endif; ?>
            <?php if(isset($globalSettings['contact_phone'])): ?>
            <a href="tel:<?php echo e($globalSettings['contact_phone']); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
                <?php echo e($globalSettings['contact_phone']); ?>

            </a>
            <?php endif; ?>
        </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container text-center">
      <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Setting::get('site_name', 'Global Articles')); ?>.</p>
    </div>
  </div>
</footer>

<!-- Main Layout Scripts -->
<script src="<?php echo e(asset('js/main-layout.js')); ?>"></script>
<script src="<?php echo e(asset('js/auto-embed.js')); ?>"></script>
</body>
</html>
<?php /**PATH D:\GLOBALARTICLE\resources\views/layouts/main.blade.php ENDPATH**/ ?>