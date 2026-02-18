<x-admin-layout>
    @section('header', 'Team Management')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <!-- Toolbar -->
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Team Members</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage administrators, editors, and authors who contribute to Global Articles.</p>
            </div>

            <a href="{{ route('admin.users.create') }}" 
               style="background: var(--primary);" 
               onmouseover="this.style.background='var(--primary-hover)'"
               onmouseout="this.style.background='var(--primary)'"
               class="text-white text-sm font-bold py-2.5 px-6 rounded-lg transition flex items-center gap-2 shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Member
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Name & Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Articles</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-gray-900 dark:text-gray-100 block">{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <x-role-badge :role="$user->role" />
                        </td>
                        <td class="px-6 py-4">
                             <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->articles_count }} Articles</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition" title="Edit User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this team member?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition" title="Delete User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-6 border-t border-gray-100 dark:border-gray-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
