@php
    $questionStatusColors = [
        'unanswered' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
        'partially_answered' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
        'answered' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
    ];
    $questionStatusLabels = config('woo.question_statuses');
    
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1 text-sm',
    ];
    $classes = ($sizeClasses[$size] ?? $sizeClasses['md']) . ' font-medium rounded-full';
@endphp

<span class="inline-flex {{ $classes }} {{ $questionStatusColors[$question->status] ?? 'bg-gray-100 text-gray-600' }}">
    {{ $questionStatusLabels[$question->status] ?? $question->status }}
</span>

