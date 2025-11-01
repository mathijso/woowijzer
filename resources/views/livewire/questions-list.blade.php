<div id="questions-section">
    {{-- Active Filter Badge --}}
    @if($statusFilter)
        <div class="p-3 mb-4 bg-blue-50 rounded-lg border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800">
            <div class="flex justify-between items-center">
                <div class="flex gap-2 items-center">
                    <svg class="w-4 h-4 text-rijksblauw dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-rijksblauw dark:text-blue-400">
                        Filter actief:
                        @php
                            $filterLabels = [
                                'unanswered' => 'Onbeantwoord',
                                'partially_answered' => 'Gedeeltelijk beantwoord',
                                'answered' => 'Beantwoord',
                            ];
                        @endphp
                        {{ $filterLabels[$statusFilter] ?? ucfirst($statusFilter) }}
                    </span>
                </div>
                <button wire:click="clearFilter"
                        class="text-xs font-medium transition-colors text-rijksblauw hover:text-rijksdonkerblauw dark:text-blue-400 dark:hover:text-blue-300">
                    Wis filter
                </button>
            </div>
        </div>
    @endif

    {{-- Search Input --}}
    <div class="mb-4">
        <div class="relative">
            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Zoek in vragen..."
                   class="block px-4 py-2 pl-10 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
        </div>
    </div>

    {{-- Questions List --}}
    <div class="space-y-3">
        @forelse($questions as $question)
            @php
                $questionStatusColors = [
                    'unanswered' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                    'partially_answered' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                    'answered' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                ];
                $questionStatusLabels = config('woo.question_statuses');

                // Calculate display number: (current page - 1) * per page + index + 1
                // e.g., page 1: (1-1)*15 + 0 + 1 = 1, page 2: (2-1)*15 + 0 + 1 = 16
                $displayNumber = ($questions->currentPage() - 1) * $questions->perPage() + $loop->index + 1;
            @endphp
            <a href="{{ route('cases.questions.show', [$wooRequest, $question]) }}"
               class="block p-4 rounded-lg transition bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex gap-4 items-start">
                            <div class="flex flex-shrink-0 justify-center items-center w-8 h-8 text-sm font-semibold bg-blue-100 rounded-full text-rijksblauw dark:bg-blue-900/20 dark:text-blue-400">
                                {{ $displayNumber }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $question->question_text }}</p>
                                <div class="flex gap-2 items-center mt-2">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $questionStatusColors[$question->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $questionStatusLabels[$question->status] ?? $question->status }}
                                    </span>
                                    @if($question->documents_count > 0)
                                        <span class="text-xs text-neutral-600 dark:text-neutral-400">
                                            {{ $question->documents_count }} document(en) gekoppeld
                                        </span>
                                    @endif
                                </div>
                                @if($question->ai_summary)
                                    <div class="p-3 mt-3 text-xs bg-blue-50 rounded-lg text-neutral-700 dark:bg-blue-900/10 dark:text-neutral-300">
                                        <strong class="block font-semibold text-blue-900 dark:text-blue-200">AI Samenvatting:</strong>
                                        <div class="mt-1 whitespace-pre-wrap">{{ Str::limit($question->ai_summary, 150) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <p class="py-4 text-sm text-center text-neutral-600 dark:text-neutral-400">
                @if($search)
                    Geen vragen gevonden voor "{{ $search }}"
                @else
                    Nog geen vragen geëxtraheerd uit het document
                @endif
            </p>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($questions->hasPages())
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    @endif
</div>

