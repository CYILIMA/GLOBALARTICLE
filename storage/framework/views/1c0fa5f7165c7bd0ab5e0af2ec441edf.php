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
    <?php $__env->startSection('header', 'New Article'); ?>

    <div class="max-w-4xl mx-auto space-y-6">
        <form action="<?php echo e(route('news.store')); ?>" method="POST" enctype="multipart/form-data" id="articleForm">
            <?php echo csrf_field(); ?>
            
            <!-- Hidden field for block content -->
            <input type="hidden" name="content_blocks" id="content_blocks">
            <input type="hidden" name="content_version" value="v2">

            <!-- MAIN CONTENT -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 space-y-6">
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Article Title</label>
                    <input type="text" name="title" id="title" required 
                           class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white text-lg font-medium" 
                           placeholder="Enter a catchy title...">
                </div>

                <!-- Category & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select name="category_id" id="category_id" required 
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white">
                            <option value="">Select a Category...</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($categories->isEmpty()): ?>
                            <p class="text-xs text-red-500 mt-1">No categories found. Please create one first.</p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" required 
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white">
                            <option value="draft">Draft (Save for later)</option>
                            <option value="published">Published (Visible immediately)</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <!-- Block Editor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Article Content</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        Use the block editor below to structure your article. The first paragraph will automatically be styled as an introduction.
                    </p>
                    <div id="editorjs" style="border: 2px solid #e5e7eb; border-radius: 8px; min-height: 400px; background: white;" class="dark:bg-gray-900 dark:border-gray-700"></div>
                </div>
            </div>

            <!-- SIDEBAR SETTINGS -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 border-b pb-2 dark:border-gray-700">Publishing Settings</h3>

                <!-- Featured Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Featured Image</label>
                    <input type="file" name="image" id="image" accept="image/*" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition dark:file:bg-gray-700 dark:file:text-gray-300">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x630px. This is separate from inline article images.</p>
                </div>

                <!-- SEO -->
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SEO Title (Optional)</label>
                        <input type="text" name="meta_title" id="meta_title" 
                               class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded shadow-sm text-sm dark:text-white">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SEO Description (Optional)</label>
                        <textarea name="meta_description" id="meta_description" rows="3" 
                                  class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded shadow-sm text-sm dark:text-white"></textarea>
                    </div>
                </div>

                <!-- Breaking News -->
                <div class="flex items-start gap-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800/50 flex-col">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center h-5">
                            <input name="breaking" id="breaking" type="checkbox" value="1" 
                                   class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded dark:bg-gray-900 dark:border-gray-600">
                        </div>
                        <div class="ml-1 text-sm">
                            <label for="breaking" class="font-medium text-red-800 dark:text-red-400">Breaking News Alert</label>
                            <p class="text-red-600 dark:text-red-400/80 text-xs">Sends email to all subscribers immediately.</p>
                        </div>
                    </div>
                    
                    <div class="w-full pl-6">
                        <label for="breaking_expires_at" class="block text-xs font-medium text-red-800 dark:text-red-400 mb-1">Expires At (Optional)</label>
                        <input type="datetime-local" name="breaking_expires_at" id="breaking_expires_at" 
                               class="w-full border-red-200 dark:border-red-800/50 dark:bg-gray-900 rounded shadow-sm text-sm text-gray-700 dark:text-gray-300 focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-400 mt-1">Leave empty to default to 24 hours.</p>
                    </div>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="flex items-center justify-end gap-4 border-t pt-6 dark:border-gray-700">
                <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Save Article
                </button>
            </div>

        </form>
    </div>
    <?php $__env->startPush('scripts'); ?>
    <!-- Editor.js Core and Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.28.2?v=<?php echo e(time()); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.7.0?v=<?php echo e(time()); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.8.1?v=<?php echo e(time()); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@2.5.0?v=<?php echo e(time()); ?>"></script>

    <!-- Article Editor Initialization -->
    <script>
    // alert('NEW EDITOR CODE LOADED!'); // Debug alert removed
    console.log('=== EDITOR SCRIPT LOADED AT <?php echo e(date("Y-m-d H:i:s")); ?> ===');
console.log('Script loaded, waiting for DOMContentLoaded...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded!');
    console.log('EditorJS available?', typeof EditorJS);
    console.log('Header available?', typeof Header);
    console.log('ImageTool available?', typeof ImageTool);
    console.log('Quote available?', typeof Quote);
    console.log('Editor div exists?', document.getElementById('editorjs'));
    
    let editor; // Declare globally within this scope

    try {
        // Initialize Editor.js
        editor = new EditorJS({ // Assign to outer variable
            holder: 'editorjs',
            
            // Start with an empty paragraph block
            data: {
                blocks: [
                    {
                        type: 'paragraph',
                        data: {
                            text: ''
                        }
                    }
                ]
            },
            
            autofocus: true,
            
            tools: {
                header: {
                    class: Header,
                    config: {
                        placeholder: 'Enter a heading',
                        levels: [2, 3],
                        defaultLevel: 2
                    }
                },
                
                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: '/admin/upload-article-image',
                        },
                        field: 'image',
                        types: 'image/*',
                        captionPlaceholder: 'Enter image caption (required)',
                        buttonContent: 'Select an image',
                        additionalRequestHeaders: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }
                },
                
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    config: {
                        quotePlaceholder: 'Enter a quote',
                        captionPlaceholder: 'Quote author (optional)'
                    }
                }
            },
            
            placeholder: 'Start writing your article...',
            minHeight: 300,
            
            onReady: () => {
                console.log('Editor.js is ready!');
            }
        });
        
        console.log('Editor instance created:', editor);
    } catch (error) {
        console.error('Error initializing Editor.js:', error);
        alert('Error loading editor: ' + error.message);
    }
    
    // Handle form submission
    document.getElementById('articleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!editor) {
            alert('Editor not initialized properly');
            return;
        }

        try {
            // Save editor content
            const outputData = await editor.save();
            
            // Validate that all images have captions
            const imageBlocks = outputData.blocks.filter(block => block.type === 'image');
            for (let img of imageBlocks) {
                if (!img.data.caption || img.data.caption.trim() === '') {
                    alert('All images must have captions');
                    return;
                }
            }
            
            // Mark first paragraph as intro
            if (outputData.blocks.length > 0 && outputData.blocks[0].type === 'paragraph') {
                outputData.blocks[0].data.isIntro = true;
            }
            
            // Set the hidden field value
            document.getElementById('content_blocks').value = JSON.stringify(outputData);
            
            // Submit the form
            this.submit();
        } catch (error) {
            alert('Error saving article: ' + error.message);
            console.error('Save error:', error);
        }
    });
});
</script>

<style>
/* Editor.js Custom Styling */
#editorjs {
    padding: 20px;
}

.ce-block__content,
.ce-toolbar__content {
    max-width: 700px;
    margin: 0 auto;
}

.codex-editor__redactor {
    padding-bottom: 100px !important;
}

.ce-paragraph {
    line-height: 1.7;
    font-size: 18px;
}

/* Dark mode support */
.dark #editorjs {
    color: #f3f4f6;
}

.dark .ce-block__content {
    color: #f3f4f6;
}

.dark .ce-toolbar__plus,
.dark .ce-toolbar__settings-btn {
    color: #f3f4f6;
}

.dark .ce-inline-toolbar,
.dark .ce-conversion-toolbar,
.dark .ce-settings {
    background: #1f2937;
    border-color: #374151;
}

.dark .ce-inline-tool,
.dark .ce-conversion-tool,
.dark .ce-settings__button {
    color: #f3f4f6;
}

.dark .ce-inline-tool:hover,
.dark .ce-settings__button:hover {
    background-color: #374151;
}
</style>
<?php $__env->stopPush(); ?>
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
<?php /**PATH D:\GLOBALARTICLE\resources\views/admin/news/create.blade.php ENDPATH**/ ?>