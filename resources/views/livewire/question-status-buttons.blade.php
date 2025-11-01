<div>
    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
        Status
    </label>
    <div class="flex gap-2">
        @php
            $statuses = config('woo.question_statuses');
            $statusColors = [
                'unanswered' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
                'partially_answered' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/30',
                'answered' => 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30',
            ];
            $activeStatusColors = [
                'unanswered' => 'bg-gray-200 text-gray-900 border-2 border-gray-400 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-500',
                'partially_answered' => 'bg-yellow-200 text-yellow-900 border-2 border-yellow-500 dark:bg-yellow-900/40 dark:text-yellow-200 dark:border-yellow-400',
                'answered' => 'bg-green-200 text-green-900 border-2 border-green-500 dark:bg-green-900/40 dark:text-green-200 dark:border-green-400',
            ];
        @endphp

        @foreach($statuses as $key => $label)
            <button type="button"
                    wire:click="updateStatus('{{ $key }}')"
                    wire:loading.attr="disabled"
                    class="flex-1 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $question->status === $key ? $activeStatusColors[$key] : $statusColors[$key] }} disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="updateStatus">
                    {{ $label }}
                </span>
                <span wire:loading wire:target="updateStatus" class="inline-flex items-center">
                    <svg class="mr-1 w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Bezig...
                </span>
            </button>
        @endforeach
    </div>

    @if(session()->has('status-updated'))
        <p class="mt-2 text-sm text-green-600 dark:text-green-400">
            {{ session('status-updated') }}
        </p>
    @endif
</div>

