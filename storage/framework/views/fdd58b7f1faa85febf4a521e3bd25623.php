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
    <?php $__env->startSection('header', 'System Settings'); ?>

    <div class="max-w-4xl" x-data="{ activeTab: 'general' }">
        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-t-lg shadow-sm border border-b-0 border-gray-200 dark:border-gray-700">
            <div class="flex border-b border-gray-200 dark:border-gray-700">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    General
                </button>
                <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Email
                </button>
                <button @click="activeTab = 'content'" :class="activeTab === 'content' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Content
                </button>
                <button @click="activeTab = 'social'" :class="activeTab === 'social' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Social
                </button>
                <button @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Contact
                </button>
                <button @click="activeTab = 'advanced'" :class="activeTab === 'advanced' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'" class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Advanced
                </button>
            </div>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" class="bg-white dark:bg-gray-800 rounded-b-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <?php echo csrf_field(); ?>

            <!-- General Settings -->
            <div x-show="activeTab === 'general'" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">General Settings</h3>
                <?php $__currentLoopData = $settings['general'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?>

                            <?php if($setting->description): ?>
                                <span class="text-xs text-gray-500 block"><?php echo e($setting->description); ?></span>
                            <?php endif; ?>
                        </label>
                        <?php if($setting->type === 'boolean'): ?>
                            <input type="checkbox" name="settings[<?php echo e($setting->key); ?>]" value="1" <?php echo e($setting->value ? 'checked' : ''); ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <?php else: ?>
                            <input type="<?php echo e($setting->type === 'integer' ? 'number' : 'text'); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e($setting->value); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Email Settings -->
            <div x-show="activeTab === 'email'" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Email Settings</h3>
                <?php $__currentLoopData = $settings['email'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?>

                            <?php if($setting->description): ?>
                                <span class="text-xs text-gray-500 block"><?php echo e($setting->description); ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="text" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e($setting->value); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Content Settings -->
            <div x-show="activeTab === 'content'" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Content Settings</h3>
                <?php $__currentLoopData = $settings['content'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?>

                            <?php if($setting->description): ?>
                                <span class="text-xs text-gray-500 block"><?php echo e($setting->description); ?></span>
                            <?php endif; ?>
                        </label>
                        <?php if($setting->type === 'boolean'): ?>
                            <input type="checkbox" name="settings[<?php echo e($setting->key); ?>]" value="1" <?php echo e($setting->value ? 'checked' : ''); ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <?php else: ?>
                            <input type="<?php echo e($setting->type === 'integer' ? 'number' : 'text'); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e($setting->value); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Social Settings -->
            <div x-show="activeTab === 'social'" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Social Media Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__currentLoopData = $settings['social'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(ucwords(str_replace(['_', 'social '], [' ', ''], $setting->key))); ?>

                        </label>
                        <input type="url" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e($setting->value); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white" placeholder="https://...">
                        <?php if($setting->description): ?>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Contact Settings -->
            <div x-show="activeTab === 'contact'" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php $__currentLoopData = $settings['contact'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(ucwords(str_replace(['_', 'contact '], [' ', ''], $setting->key))); ?>

                        </label>
                        <input type="text" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e($setting->value); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                        <?php if($setting->description): ?>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div x-show="activeTab === 'advanced'" class="space-y-4" style="display: none;">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Advanced Settings</h3>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">⚠️ Warning: Changing these settings may affect system performance and functionality.</p>
                </div>
                <?php $__currentLoopData = $settings['advanced'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?>

                            <?php if($setting->description): ?>
                                <span class="text-xs text-gray-500 block"><?php echo e($setting->description); ?></span>
                            <?php endif; ?>
                        </label>
                        <?php if($setting->type === 'boolean'): ?>
                            <input type="checkbox" name="settings[<?php echo e($setting->key); ?>]" value="1" <?php echo e($setting->value ? 'checked' : ''); ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <?php else: ?>
                            <input type="<?php echo e($setting->type === 'integer' ? 'number' : 'text'); ?>" name="settings[<?php echo e($setting->key); ?>]" value="<?php echo e($setting->value); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                <form action="<?php echo e(route('admin.settings.clear-cache')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        🗑️ Clear Cache
                    </button>
                </form>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    Save Settings
                </button>
            </div>
        </form>
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
<?php /**PATH D:\GLOBALARTICLE\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>