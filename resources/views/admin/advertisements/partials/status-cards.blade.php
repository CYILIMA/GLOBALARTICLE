{{--
    Params:
      $current  — currently selected status value (default: 'active')
      $statuses — array from Advertisement::statusLabels()
--}}
@php
$icons = [
    'active'    => ['✅', 'Go live now'],
    'inactive'  => ['⏸️', 'Hidden for now'],
    'scheduled' => ['🕐', 'Auto-starts on start date'],
    'expired'   => ['🔴', 'Mark as completed'],
];
@endphp

@foreach($statuses as $val => $label)
@php [$icon, $desc] = $icons[$val] ?? ['📌', '']; @endphp
<label class="status-card cursor-pointer border-2 rounded-lg p-3 text-center transition-all
              {{ ($current ?? 'active') === $val
                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20'
                    : 'border-gray-200 dark:border-gray-600 hover:border-blue-300' }}">
    <input type="radio" name="status" value="{{ $val }}" class="sr-only"
           {{ ($current ?? 'active') === $val ? 'checked' : '' }}>
    <div class="text-xl mb-1">{{ $icon }}</div>
    <p class="text-xs font-bold text-gray-800 dark:text-gray-100">{{ $label }}</p>
    <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">{{ $desc }}</p>
</label>
@endforeach
