<div>
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
                   placeholder="Zoek in documenten..."
                   class="block px-4 py-2 pl-10 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
        </div>
    </div>

    {{-- Documents List --}}
    <div class="space-y-3">
        @forelse($documents as $document)
            <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
               class="flex gap-3 items-center p-3 rounded-lg transition cursor-pointer bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                <svg class="flex-shrink-0 w-5 h-5 text-rijksblauw dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <div class="flex gap-2 items-center">
                        <p class="text-sm font-medium truncate text-neutral-900 dark:text-white">{{ $document->file_name }}</p>

                    </div>
                    <div class="flex flex-wrap gap-2 items-center mt-1">
                         @if($document->relevance_score !== null)
                            <span class="px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400" title="Relevantie score">
                                {{ round($document->relevance_score * 100) }}%
                            </span>
                        @endif

                        <p class="text-xs text-neutral-600 dark:text-neutral-400">
                            {{ $document->getFileSizeFormatted() }} • {{ $document->created_at->format('d-m-Y H:i') }}
                        </p>
                        @if($document->questions_count > 0)
                            <span class="text-xs text-rijksblauw dark:text-blue-400">
                                • {{ $document->questions_count }} {{ $document->questions_count === 1 ? 'vraag' : 'vragen' }} gekoppeld
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    @if($document->api_processing_status === 'completed')
                        <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                            Verwerkt
                        </span>
                    @elseif($document->api_processing_status === 'processing')
                        <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-900/20 dark:text-yellow-400">
                            Thinking...
                        </span>
                    @elseif($document->api_processing_status === 'failed')
                        <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full dark:bg-red-900/20 dark:text-red-400">
                            Mislukt
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">
                            In wachtrij
                        </span>
                    @endif
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        @empty
            <p class="py-4 text-sm text-center text-neutral-600 dark:text-neutral-400">
                @if($search)
                    Geen documenten gevonden voor "{{ $search }}"
                @else
                    Nog geen documenten geüpload
                @endif
            </p>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($documents->hasPages())
        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    @endif
</div>

