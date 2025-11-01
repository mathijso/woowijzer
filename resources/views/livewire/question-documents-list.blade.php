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
            <div class="py-4 border-t border-neutral-200 dark:border-neutral-700 first:border-t-0">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
                           class="block">
                            <p class="text-sm font-medium text-dark hover:text-rijksblauw dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $document->file_name }}
                            </p>
                        </a>
                        <div class="flex gap-2 items-center mt-2">
                            @if($document->pivot->relevance_score)
                                <span class="px-2 py-1 text-xs font-medium text-white rounded-full bg-rijksblauw dark:bg-blue-900/20 dark:text-blue-400">
                                    {{ round($document->pivot->relevance_score * 100) }}% relevant
                                </span>
                            @endif
                            @if($document->pivot->confirmed_by_case_manager)
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                    Bevestigd
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">
                                    Niet bevestigd
                                </span>
                            @endif
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
                            @endif
                        </div>
                        @if($document->pivot->notes)
                            <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-400">
                                {{ $document->pivot->notes }}
                            </p>
                        @endif
                        <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-500">
                            Geüpload op {{ $document->created_at->format('d-m-Y H:i') }}
                        </p>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
                           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border text-neutral-700 border-neutral-300 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Bekijk
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="py-4 text-sm text-center text-neutral-600 dark:text-neutral-400">
                @if($search)
                    Geen documenten gevonden voor "{{ $search }}"
                @else
                    Deze vraag heeft nog geen gekoppelde documenten
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

