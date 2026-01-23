<x-admin-layout>
    @section('header', 'New Article')

    <div class="max-w-4xl mx-auto space-y-6" x-data>
        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if($categories->isEmpty())
                            <p class="text-xs text-red-500 mt-1">No categories found. Please create one first.</p>
                        @endif
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

                <!-- Content -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Article Content</label>
                        <button type="button" @click="$dispatch('open-media-modal')" class="text-xs bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-md transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Insert from Media Library
                        </button>
                    </div>
                    <textarea name="content" id="content" rows="12" required 
                              class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white font-mono text-sm leading-relaxed" 
                              placeholder="Write your article here..."></textarea>
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
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x630px</p>
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
                        <p class="text-xs text-gray-400 mt-1">Leaves empty to default to 24 hours.</p>
                    </div>
                </div>
            </div>
            
            <x-media-library-modal />

            <!-- ACTIONS -->
            <div class="flex items-center justify-end gap-4 border-t pt-6 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Save Article
                </button>
            </div>

        </form>
    </div>
</x-admin-layout>

@push('scripts')
<style>
    /* ... existing styles ... */
    .ck-editor__editable_inline { min-height: 400px; }
    .dark .ck-editor__editable { background-color: #1f2937 !important; color: #f3f4f6 !important; }
    .dark .ck.ck-toolbar, .dark .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { background-color: #1f2937; border-color: #374151; }
    .dark .ck.ck-toolbar__items { background-color: #1f2937; }
    .dark .ck.ck-button { color: #f3f4f6; }
    .dark .ck.ck-button:hover { background-color: #374151; }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(document.querySelector('#content')){
            let editorInstance;

            if (typeof window.ClassicEditor !== 'undefined') {
                window.ClassicEditor
                    .create(document.querySelector('#content'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
                    })
                    .then(editor => {
                         editorInstance = editor;
                         console.log( 'Editor was initialized', editor );
                    })
                    .catch(error => {
                        console.error('CKEditor Init Error:', error);
                    });
            } else {
                console.error('ClassicEditor is not defined on window.');
            }

            // Listen for media selection
            window.addEventListener('media-selected', event => {
                if(editorInstance) {
                    const { url, type, alt, name } = event.detail;
                    if(type === 'image') {
                        // Use execute 'insertImage' if plugin is available, otherwise insert HTML
                        editorInstance.execute('insertImage', { source: url });
                    } else {
                         editorInstance.model.change(writer => {
                             const link = writer.createText( name, { linkHref: url } );
                             editorInstance.model.insertContent( link, editorInstance.model.document.selection );
                         });
                    }
                }
            });
        }
    });
</script>
