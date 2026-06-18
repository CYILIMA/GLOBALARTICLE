<x-admin-layout>
    @section('header', 'Create Category')

    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name</label>
                <input type="text" name="name" id="name" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-gray-100" placeholder="e.g. Technology">
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                          placeholder="A short description shown on the category page and used for search engine results."
                          class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:text-gray-100">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Used as the category page intro and SEO meta description.</p>
            </div>

            <div class="mb-6">
                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Badge Color (Hex)</label>
                <div class="flex items-center gap-4">
                    <input type="color" name="color" id="color" value="#3b82f6" class="h-10 w-16 p-0 border-0 rounded cursor-pointer">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Pick a color for the category badge</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('admin.categories.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white">Cancel</a>
                <button type="submit" 
                        style="background: var(--primary);" 
                        onmouseover="this.style.background='var(--primary-hover)'"
                        onmouseout="this.style.background='var(--primary)'"
                        class="text-white font-bold py-2 px-6 rounded shadow">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
