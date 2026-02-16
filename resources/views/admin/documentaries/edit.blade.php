<x-admin-layout>
    @section('header', 'Edit Documentary')

    <div class="max-w-4xl">
        <form action="{{ route('admin.documentaries.update', $documentary) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                        <input type="text" name="title" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white" value="{{ old('title', $documentary->title) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                        <select name="category_id" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $documentary->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                    <textarea name="description" rows="3" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">{{ old('description', $documentary->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content *</label>
                    <textarea name="content" id="content" rows="10" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">{{ old('content', $documentary->content) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Thumbnail</label>
                    @if($documentary->thumbnail)
                        <img src="{{ asset('storage/' . $documentary->thumbnail) }}" alt="Current thumbnail" class="w-48 h-auto rounded mb-2">
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current thumbnail</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Video URL</label>
                    <input type="url" name="video_url" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white" value="{{ old('video_url', $documentary->video_url) }}" placeholder="https://youtube.com/watch?v=...">
                    <p class="text-xs text-gray-500 mt-1">YouTube, Vimeo, or direct video link</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg dark:text-white">
                        <option value="draft" {{ old('status', $documentary->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        @can('publish', App\Models\Documentary::class)
                            <option value="published" {{ old('status', $documentary->status) === 'published' ? 'selected' : '' }}>Published</option>
                        @else
                            @if($documentary->status === 'published')
                                <option value="published" selected>Published (Read-only)</option>
                            @endif
                        @endcan
                    </select>
                    @cannot('publish', App\Models\Documentary::class)
                        @if($documentary->status !== 'published')
                            <p class="text-[10px] text-orange-500 mt-1 uppercase font-bold tracking-tighter">Requires Administrator Approval to Publish</p>
                        @endif
                    @endcannot
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.documentaries.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 rounded-lg transition border" style="background: var(--button-bg); color: var(--button-text); border-color: var(--button-border);">Update Documentary</button>
            </div>
        </form>
    </div>
</x-admin-layout>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content')).catch(error => console.error(error));
</script>
@endpush
