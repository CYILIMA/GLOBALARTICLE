<x-admin-layout>
    @section('header', 'Advertisements')

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <!-- Header Bar -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap gap-3 justify-between items-center">
            <div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100">All Advertisements</h3>
                <p class="text-xs text-gray-400 mt-0.5">Manage direct ad sales banners displayed on the public site.</p>
            </div>
            <a href="{{ route('admin.advertisements.create') }}"
               style="background: var(--primary);"
               onmouseover="this.style.background='var(--primary-hover)'"
               onmouseout="this.style.background='var(--primary)'"
               class="text-white text-sm font-bold py-2 px-4 rounded transition whitespace-nowrap">
                + New Advertisement
            </a>
        </div>

        @if($ads->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[1050px]">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Banner</th>
                        <th class="px-4 py-3">Advertiser</th>
                        <th class="px-4 py-3">Position</th>
                        <th class="px-4 py-3">Tier</th>
                        <th class="px-4 py-3">Schedule</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Impressions</th>
                        <th class="px-4 py-3 text-center">Clicks</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($ads as $ad)
                    @php $computed = $ad->computedStatus(); @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">

                        <!-- Banner thumbnail -->
                        <td class="px-4 py-3">
                            <div class="relative inline-block">
                                <img src="{{ Storage::url($ad->banner_image) }}"
                                     alt="{{ $ad->banner_title }}"
                                     class="h-10 w-24 object-cover rounded border border-gray-200 dark:border-gray-600">
                                @if($ad->display_type === 'premium')
                                <span class="absolute -top-1.5 -right-1.5 text-xs leading-none">⭐</span>
                                @endif
                            </div>
                        </td>

                        <!-- Advertiser & title -->
                        <td class="px-4 py-3">
                            <p class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $ad->advertiser_name }}</p>
                            <p class="text-xs text-gray-400 truncate max-w-[160px]">{{ $ad->banner_title }}</p>
                            @if($ad->price)
                            <p class="text-xs font-semibold text-green-600 dark:text-green-400 mt-0.5">
                                RWF {{ number_format($ad->price, 0) }}
                            </p>
                            @endif
                        </td>

                        <!-- Position -->
                        <td class="px-4 py-3">
                            <span class="inline-block bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold px-2 py-1 rounded">
                                {{ $positions[$ad->position] ?? $ad->position }}
                            </span>
                        </td>

                        <!-- Tier badge -->
                        <td class="px-4 py-3">
                            @if($ad->display_type === 'premium')
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 border border-yellow-300 dark:border-yellow-700">
                                    ⭐ Premium
                                </span>
                                <p class="text-[10px] text-gray-400 mt-0.5">Always On</p>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    🔄 Standard
                                </span>
                                <p class="text-[10px] text-gray-400 mt-0.5">Rotating</p>
                            @endif
                        </td>

                        <!-- Schedule -->
                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                            {{ $ad->start_date->format('d M Y') }}<br>
                            <span class="text-gray-400">→ {{ $ad->end_date->format('d M Y') }}</span>
                        </td>

                        <!-- Status badge -->
                        <td class="px-4 py-3">
                            @if($computed === 'active')
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Active
                                </span>
                            @elseif($computed === 'scheduled')
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 inline-block"></span> Scheduled
                                </span>
                            @elseif($computed === 'expired')
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span> Expired
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Impressions -->
                        <td class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ number_format($ad->impressions) }}
                        </td>

                        <!-- Clicks + CTR -->
                        <td class="px-4 py-3 text-center">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ number_format($ad->clicks) }}</p>
                            @if($ad->impressions > 0)
                            <p class="text-[10px] text-gray-400">{{ number_format(($ad->clicks / $ad->impressions) * 100, 1) }}% CTR</p>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ route('admin.advertisements.edit', $ad) }}"
                                   class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">Edit</a>
                                <form method="POST"
                                      action="{{ route('admin.advertisements.destroy', $ad) }}"
                                      onsubmit="return confirm('Delete this advertisement? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 dark:text-red-400 hover:underline text-sm font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($ads->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $ads->links() }}
        </div>
        @endif

        @else
        <div class="py-20 text-center">
            <div class="text-5xl mb-4">📢</div>
            <p class="text-gray-500 dark:text-gray-400 font-semibold">No advertisements yet.</p>
            <p class="text-gray-400 text-sm mt-1">Create your first ad to start earning from your platform.</p>
            <a href="{{ route('admin.advertisements.create') }}"
               style="background: var(--primary);"
               onmouseover="this.style.background='var(--primary-hover)'"
               onmouseout="this.style.background='var(--primary)'"
               class="inline-block mt-4 text-white text-sm font-bold py-2 px-5 rounded transition">
                + Create First Advertisement
            </a>
        </div>
        @endif
    </div>
</x-admin-layout>
