@props(['status'])

@php
    $status = $status ?? 'draft';
    $classes = match($status) {
        'published', 'active' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
        'draft', 'inactive' => 'bg-gray-100 text-gray-800 dark:bg-gray-800/50 dark:text-gray-400 border-gray-200 dark:border-gray-700',
        'scheduled' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 border-amber-200 dark:border-amber-800',
        'archived', 'unsubscribed' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400 border-red-200 dark:border-red-800',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-800/50 dark:text-gray-400 border-gray-200 dark:border-gray-700'
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border transition-colors {$classes}"]) }}>
    {{ ucfirst($status) }}
</span>
