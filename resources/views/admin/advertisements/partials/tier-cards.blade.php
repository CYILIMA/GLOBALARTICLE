{{--
    Params:
      $currentTier  — currently selected display_type value (default: 'standard')
      $displayTypes — array from Advertisement::displayTypeLabels()
--}}
@php
$tier_meta = [
    'premium' => [
        'icon'         => '⭐',
        'desc'         => 'Shown <strong>100% of the time</strong>. Blocks standard ads from showing in the same position. Ideal for MTN, BK, or any brand paying for full visibility.',
        'price_label'  => 'HIGHEST PRICE',
        'active_cls'   => 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20',
        'hover_cls'    => 'hover:border-yellow-400',
        'badge_cls'    => 'text-yellow-700 dark:text-yellow-400 bg-yellow-100 dark:bg-yellow-900/30',
    ],
    'standard' => [
        'icon'         => '🔄',
        'desc'         => 'Rotates every 7 seconds alongside other standard advertisers. Best for budget-friendly campaigns.',
        'price_label'  => 'MEDIUM PRICE',
        'active_cls'   => 'border-blue-500 bg-blue-50 dark:bg-blue-900/20',
        'hover_cls'    => 'hover:border-blue-400',
        'badge_cls'    => 'text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30',
    ],
];
$currentTier = $currentTier ?? 'standard';
@endphp

@foreach($displayTypes as $val => $label)
@php $m = $tier_meta[$val]; @endphp
<label class="tier-card cursor-pointer border-2 rounded-xl p-4 transition-all
              {{ $currentTier === $val ? $m['active_cls'] : 'border-gray-200 dark:border-gray-600 ' . $m['hover_cls'] }}">
    <input type="radio" name="display_type" value="{{ $val }}" class="sr-only"
           {{ $currentTier === $val ? 'checked' : '' }}>
    <div class="flex items-start gap-3">
        <div class="text-2xl mt-0.5">{{ $m['icon'] }}</div>
        <div>
            <p class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $label }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">{!! $m['desc'] !!}</p>
            <span class="inline-block mt-2 text-[10px] font-bold {{ $m['badge_cls'] }} px-2 py-0.5 rounded-full">
                {{ $m['price_label'] }}
            </span>
        </div>
    </div>
</label>
@endforeach
