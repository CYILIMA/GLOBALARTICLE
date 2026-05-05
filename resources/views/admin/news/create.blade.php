<x-admin-layout>
    @section('header', 'New Article')

    <div class="max-w-4xl mx-auto space-y-6">
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm dark:bg-red-900/50 dark:text-red-200" role="alert">
                <p class="font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
            @csrf

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

                <!-- Category, Status, & Author -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select name="category_id" id="category_id" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white">
                            <option value="">Select a Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" required
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Save for later)</option>
                            @if(auth()->user()->role !== 'author')
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Visible immediately)</option>
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            @else
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Submit for Approval)</option>
                            @endif
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @if(auth()->user()->role === 'author')
                            <p class="text-[10px] text-orange-500 mt-1 uppercase font-bold tracking-tighter">Requires Administrator Approval to Publish</p>
                        @endif
                    </div>
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Primary Author</label>
                        <select name="user_id" id="user_id" required
                                {{ auth()->user()->role === 'author' ? 'disabled' : '' }}
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-white {{ auth()->user()->role === 'author' ? 'bg-gray-50 opacity-75' : '' }}">
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ (old('user_id', auth()->id()) == $author->id) ? 'selected' : '' }}>
                                    {{ $author->name }} ({{ ucfirst($author->role) }})
                                </option>
                            @endforeach
                        </select>
                        @if(auth()->user()->role === 'author')
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        @endif
                    </div>
                </div>

                <!-- Block Editor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Article Content</label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                        Use the block editor below to structure your article. The first paragraph will automatically be styled as an introduction.
                    </p>
                    <div id="editorjs" style="min-height: 400px;" class="bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-lg shadow-inner"></div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">You can insert inline links directly inside paragraphs using the link tool, or use the dedicated link block for stand-alone calls to action.</p>
                </div>
            </div>

            <!-- SIDEBAR SETTINGS -->
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 border-b pb-2 dark:border-gray-700">Publishing Settings</h3>

                <!-- Article Thumbnail -->
                <div>
                    <label for="image" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Article Thumbnail</label>
                    <div class="relative group">
                        <input type="file" name="image" id="image" accept="{{ \App\Support\AllowedImageUploads::acceptAttribute() }}"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer">
                    </div>
                    <div class="mt-2 flex flex-col gap-1">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Recommended: 1200x630px (Landscape)</p>
                        <p class="text-[10px] text-orange-500/80 dark:text-orange-400/80 font-bold italic">Max size: 20MB. For larger images, please compress before uploading.</p>
                    </div>

                    <div class="mt-4">
                        <label for="image_source" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Image Source / Credit</label>
                        <input type="text" name="image_source" id="image_source" value="{{ old('image_source') }}"
                               class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">Optional. Use this field to acknowledge the thumbnail image source or author.</p>
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

                    <div class="w-full pl-6 space-y-2">
                        <label class="block text-xs font-medium text-red-800 dark:text-red-400">Duration (Optional)</label>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="breaking_duration" id="breaking_duration"
                                   min="1" max="8760" value="{{ old('breaking_duration') }}" placeholder="24"
                                   class="w-24 border-red-200 dark:border-red-800/50 dark:bg-gray-900 rounded shadow-sm text-sm text-gray-700 dark:text-gray-300 focus:ring-red-500 focus:border-red-500">
                            <select name="breaking_duration_unit" id="breaking_duration_unit"
                                    class="border-red-200 dark:border-red-800/50 dark:bg-gray-900 rounded shadow-sm text-sm text-gray-700 dark:text-gray-300 focus:ring-red-500 focus:border-red-500">
                                <option value="hours" {{ old('breaking_duration_unit', 'hours') === 'hours' ? 'selected' : '' }}>Hours</option>
                                <option value="days"  {{ old('breaking_duration_unit') === 'days'  ? 'selected' : '' }}>Days</option>
                            </select>
                        </div>
                        <p class="text-xs text-gray-400">Leave empty to default to <strong>24 hours</strong>.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 border-t pt-6 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">Cancel</a>
                <button type="submit" class="font-bold py-3 px-10 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5"
                        style="background: var(--primary); color: #fff;">
                    {{ auth()->user()->role === 'author' ? 'Publish Article' : 'Save Article' }}
                </button>
            </div>

        </form>
    </div>

    <style>
        /* Editor.js Dark Mode Overrides */
        .dark .ce-block { color: #e5e7eb; }
        .dark .ce-paragraph { color: #e5e7eb; }
        .dark .ce-header { color: #f3f4f6; }
        .dark .ce-toolbar__actions { background-color: #1f2937; border-radius: 4px; }
        .dark .ce-toolbar__plus, .dark .ce-toolbar__settings-btn { color: #9ca3af; background-color: #374151; }
        .dark .ce-toolbar__plus:hover, .dark .ce-toolbar__settings-btn:hover { color: white; background-color: #4b5563; }
        .dark .ce-popover { background: #1f2937; border: 1px solid #374151; color: #e5e7eb; }
        .dark .ce-popover__item-icon { background: #374151; color: #e5e7eb; }
        .dark .ce-popover__item:hover { background: #374151; }
        .dark .ce-inline-toolbar { background: #1f2937; border: 1px solid #374151; color: #e5e7eb; }
        .dark .ce-inline-tool:hover { background: #374151; }
        .dark .cdx-input { background: #111827; border: 1px solid #374151; color: #e5e7eb; }
        /* Placeholder color */
        .dark .ce-paragraph[data-placeholder]:empty::before { color: #6b7280; }
        .dark .ce-header[data-placeholder]:empty::before { color: #6b7280; }
    </style>
    @push('scripts')
    <!-- Editor.js Core and Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.28.2?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.7.0?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.8.1?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@2.5.0?v={{ time() }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@2.4.0"></script>

    <script>
    class ArticleLinkTool {
        static get toolbox() {
            return {
                title: 'Link',
                icon: ''
            };
        }

        constructor({ data }) {
            this.data = data || { text: '', url: '' };
            this.wrapper = document.createElement('div');
            this.wrapper.classList.add('cdx-block');
        }

        render() {
            const textInput = document.createElement('input');
            textInput.type = 'text';
            textInput.placeholder = 'Enter link text';
            textInput.value = this.data.text || '';
            textInput.className = 'cdx-input';

            const urlInput = document.createElement('input');
            urlInput.type = 'url';
            urlInput.placeholder = 'Enter link URL';
            urlInput.value = this.data.url || '';
            urlInput.className = 'cdx-input mt-2';

            this.textInput = textInput;
            this.urlInput = urlInput;

            this.wrapper.appendChild(textInput);
            this.wrapper.appendChild(urlInput);
            return this.wrapper;
        }

        save() {
            return {
                text: this.textInput.value.trim(),
                url: this.urlInput.value.trim()
            };
        }

        validate(savedData) {
            return savedData.text && savedData.url;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        let editor; // Declare globally within this scope

    try {
        // Initialize Editor.js
        editor = new EditorJS({ // Assign to outer variable
            holder: 'editorjs',

            // Load old content if validation failed, otherwise start empty
            data: @if(old('content_blocks')) {!! old('content_blocks') !!} @else {
                blocks: [
                    {
                        type: 'paragraph',
                        data: {
                            text: ''
                        }
                    }
                ]
            } @endif,

            autofocus: true,

            tools: {
                paragraph: {
                    inlineToolbar: true
                },
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
                },
                linkTool: LinkTool,
                link: {
                    class: ArticleLinkTool,
                }
            },

            placeholder: 'Start writing your article...',
            minHeight: 300,
        });
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
@endpush
</x-admin-layout>
