@props(['role'])

@php
    $classes = match(strtolower($role)) {
        'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300 border-purple-200 dark:border-purple-800',
        'editor' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border-blue-200 dark:border-blue-800',
        'author' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300 border-gray-200 dark:border-gray-600'
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border transition-colors {$classes}"]) }}>
    {{ $role }}
</span>
