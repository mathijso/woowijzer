<div x-data="{ open: false }" class="w-full">
    {{-- Collapsible Header --}}
    <button
        type="button"
        @click="open = !open"
        class="flex justify-between items-center w-full px-3 py-2 mb-2 text-xs font-medium rounded-lg transition-all duration-200 bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-neutral-700 dark:text-neutral-300"
    >
        <div class="flex items-center gap-2">
            <span>Status wijzigen</span>
            <span class="px-2 py-0.5 text-[10px] font-medium rounded-full {{ $wooRequest->getStatusBadgeClass() }}">
                {{ $wooRequest->getStatusLabel() }}
            </span>
        </div>
        <svg
            class="w-4 h-4 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- Collapsible Content --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-[500px]"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 max-h-[500px]"
        x-transition:leave-end="opacity-0 max-h-0"
        class="overflow-hidden flex flex-col gap-2"
    >
        @php
            $statuses = config('woo.woo_request_statuses');
            $statusColors = [
                'submitted' => 'bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30',
                'in_review' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/30',
                'in_progress' => 'bg-orange-100 text-orange-700 hover:bg-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:hover:bg-orange-900/30',
                'completed' => 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30',
                'rejected' => 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30',
            ];
            $activeStatusColors = [
                'submitted' => 'bg-blue-200 text-blue-900 border-2 border-blue-500 dark:bg-blue-900/40 dark:text-blue-200 dark:border-blue-400',
                'in_review' => 'bg-yellow-200 text-yellow-900 border-2 border-yellow-500 dark:bg-yellow-900/40 dark:text-yellow-200 dark:border-yellow-400',
                'in_progress' => 'bg-orange-200 text-orange-900 border-2 border-orange-500 dark:bg-orange-900/40 dark:text-orange-200 dark:border-orange-400',
                'completed' => 'bg-green-200 text-green-900 border-2 border-green-500 dark:bg-green-900/40 dark:text-green-200 dark:border-green-400',
                'rejected' => 'bg-red-200 text-red-900 border-2 border-red-500 dark:bg-red-900/40 dark:text-red-200 dark:border-red-400',
            ];
        @endphp

        @foreach($statuses as $key => $label)
            <button
                type="button"
                wire:click="updateStatus('{{ $key }}')"
                wire:loading.attr="disabled"
                class="flex justify-center items-center w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $wooRequest->status === $key ? $activeStatusColors[$key] : $statusColors[$key] }} disabled:opacity-50 disabled:cursor-not-allowed">
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

