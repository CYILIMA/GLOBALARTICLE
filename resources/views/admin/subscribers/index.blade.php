<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subscribers') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Back to Dashboard</a>
                <a href="{{ route('admin.subscribers', ['export' => 1]) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search -->
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.subscribers') }}" class="flex gap-2">
                    <input type="text" name="search" placeholder="Search by email..." value="{{ request('search') }}" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Search</button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($subscribers->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-3 font-bold text-gray-700">Email</th>
                                    <th class="p-3 font-bold text-gray-700">Status</th>
                                    <th class="p-3 font-bold text-gray-700">Joined Date</th>
                                    <th class="p-3 font-bold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subscribers as $sub)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $sub->email }}</td>
                                        <td class="p-3">
                                            <span class="px-2 py-1 rounded text-xs text-white {{ $sub->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </td>
                                        <td class="p-3">{{ $sub->created_at->format('M d, Y') }}</td>
                                        <td class="p-3">
                                            <div class="flex items-center gap-2">
                                                <form method="POST" action="{{ route('admin.subscribers.toggle', $sub->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs px-2 py-1 rounded border {{ $sub->status == 'active' ? 'border-yellow-500 text-yellow-600 hover:bg-yellow-50' : 'border-green-500 text-green-600 hover:bg-green-50' }}">
                                                        {{ $sub->status == 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.subscribers.delete', $sub->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete this subscriber?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs px-2 py-1 rounded border border-red-500 text-red-600 hover:bg-red-50">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $subscribers->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-10">No subscribers found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
