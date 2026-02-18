<x-admin-layout>
    @section('header', 'Manage Categories')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">All Categories</h3>
            <a href="{{ route('admin.categories.create') }}" 
               style="background: var(--primary);" 
               onmouseover="this.style.background='var(--primary-hover)'"
               onmouseout="this.style.background='var(--primary)'"
               class="text-white text-sm font-bold py-2 px-4 rounded transition">
                + New Category
            </a>
        </div>
        
        @if($categories->count() > 0)
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Slug</th>
                        <th class="px-6 py-3">Badge Color</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $category->slug }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $category->color ?? '#3b82f6' }}"></span>
                            <span class="text-xs text-gray-400 ml-2 uppercase">{{ $category->color }}</span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Edit</a>
                            
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No categories found. Create one above!
            </div>
        @endif
    </div>
</x-admin-layout>
