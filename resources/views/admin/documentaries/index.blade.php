<x-admin-layout>
    @section('header', 'Documentaries')

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">All Documentaries</h2>
            <a href="{{ route('admin.documentaries.create') }}" class="px-4 py-2 rounded-lg transition border" style="background: var(--button-bg); color: var(--button-text); border-color: var(--button-border);">
                + Add Documentary
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Published</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($documentaries as $documentary)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($documentary->thumbnail)
                                        <img src="{{ asset('storage/' . $documentary->thumbnail) }}" alt="" class="w-16 h-10 object-cover rounded">
                                    @else
                                        <div class="w-16 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded flex items-center justify-center text-white">🎬</div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $documentary->title }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($documentary->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $documentary->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ ucfirst($documentary->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                {{ number_format($documentary->views) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                {{ $documentary->published_at ? $documentary->published_at->format('M d, Y') : 'Not published' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('documentaries.show', $documentary) }}" target="_blank" class="text-blue-600 hover:underline text-sm">View</a>
                                    <a href="{{ route('admin.documentaries.edit', $documentary) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                    <form action="{{ route('admin.documentaries.destroy', $documentary) }}" method="POST" onsubmit="return confirm('Delete this documentary?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No documentaries yet. Create your first one!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($documentaries->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $documentaries->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
