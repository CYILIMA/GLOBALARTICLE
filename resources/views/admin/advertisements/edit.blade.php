<x-admin-layout>
    @section('header', 'Edit Advertisement')

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-gray-100">Edit Advertisement</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $advertisement->advertiser_name }} — {{ $advertisement->banner_title }}</p>
                </div>
                <a href="{{ route('admin.advertisements.index') }}"
                   class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                    ← Back
                </a>
            </div>

            <form action="{{ route('admin.advertisements.update', $advertisement) }}" method="POST"
                  enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Advertiser Name + Banner Title --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Advertiser Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="advertiser_name" required
                               value="{{ old('advertiser_name', $advertisement->advertiser_name) }}"
                               placeholder="e.g. MTN Rwanda, Bank of Kigali"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('advertiser_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Banner Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="banner_title" required
                               value="{{ old('banner_title', $advertisement->banner_title) }}"
                               placeholder="e.g. MTN Summer Promo 2026"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('banner_title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Target URL --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Target URL <span class="text-gray-400 font-normal text-xs">(optional)</span>
                    </label>
                    <input type="url" name="target_url"
                           value="{{ old('target_url', $advertisement->target_url) }}"
                           placeholder="https://example.com/landing-page"
                           class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Leave blank if the advertiser has no website.</p>
                    @error('target_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Position --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Ad Position <span class="text-red-500">*</span>
                    </label>
                    <select name="position" required
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($positions as $value => $label)
                            <option value="{{ $value }}"
                                    {{ old('position', $advertisement->position) === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <p id="dimension-hint" class="text-xs text-blue-600 dark:text-blue-400 mt-1.5 font-medium" style="display:none;"></p>
                    @error('position')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Display Type (Tier) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Display Type <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @include('admin.advertisements.partials.tier-cards', [
                            'currentTier'  => old('display_type', $advertisement->display_type),
                            'displayTypes' => $displayTypes,
                        ])
                    </div>
                    <p class="text-xs text-gray-400 mt-2">
                        ⚠️ Premium ads take priority: standard ads for the same position won't show while a premium contract is active.
                    </p>
                    @error('display_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Contract Value --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Contract Value (RWF) <span class="text-gray-400 font-normal text-xs">(optional)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">RWF</span>
                        <input type="number" name="price" min="0" step="0.01"
                               value="{{ old('price', $advertisement->price) }}"
                               placeholder="0.00"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md pl-14 pr-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Banner Image --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Banner Image
                    </label>
                    <div class="mb-3 flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                        <img src="{{ Storage::url($advertisement->banner_image) }}"
                             alt="{{ $advertisement->banner_title }}"
                             class="h-14 w-32 object-cover rounded border border-gray-200 dark:border-gray-600">
                        <div>
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Current banner</p>
                            <p class="text-xs text-gray-400">Upload a new image to replace it, or leave blank to keep current.</p>
                        </div>
                    </div>
                    <div id="dropzone"
                         class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-5 text-center cursor-pointer hover:border-blue-400 transition-colors group">
                        <input type="file" name="banner_image" id="banner_image" accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="previewImage(this)">
                        <div id="upload-placeholder">
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 group-hover:text-blue-500">
                                Click to upload a new image
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">JPG, PNG, GIF, WebP — max 5 MB</p>
                        </div>
                        <img id="image-preview" src="#" alt="New Preview"
                             class="hidden mx-auto max-h-28 rounded object-contain mt-2">
                    </div>
                    @error('banner_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Start Date + End Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" required
                               value="{{ old('start_date', $advertisement->start_date->toDateString()) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" required
                               value="{{ old('end_date', $advertisement->end_date->toDateString()) }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @include('admin.advertisements.partials.status-cards', [
                            'current'  => old('status', $advertisement->status),
                            'statuses' => $statuses,
                        ])
                    </div>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Stats (read-only) --}}
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-600">
                    <div class="text-center">
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ number_format($advertisement->impressions) }}</p>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Impressions</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-gray-800 dark:text-gray-100">{{ number_format($advertisement->clicks) }}</p>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">
                            Total Clicks
                            @if($advertisement->impressions > 0)
                                <span class="ml-1 text-blue-500">
                                    ({{ number_format(($advertisement->clicks / $advertisement->impressions) * 100, 1) }}% CTR)
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Submit row --}}
                <div class="flex items-center justify-between gap-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <div>
                        <button type="button" onclick="document.getElementById('deleteForm').submit()"
                                class="text-sm text-red-500 dark:text-red-400 hover:underline font-medium">
                            Delete this ad
                        </button>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.advertisements.index') }}"
                           class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                            Cancel
                        </a>
                        <button type="submit"
                                style="background: var(--primary);"
                                onmouseover="this.style.background='var(--primary-hover)'"
                                onmouseout="this.style.background='var(--primary)'"
                                class="text-white font-bold py-2.5 px-6 rounded-md shadow text-sm transition">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Delete form (separate from update form) --}}
        <form id="deleteForm" method="POST" action="{{ route('admin.advertisements.destroy', $advertisement) }}"
              style="display: none;"
              onsubmit="return confirm('Permanently delete this advertisement?');">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @push('scripts')
        @include('admin.advertisements.partials.form-scripts')
    @endpush
</x-admin-layout>
