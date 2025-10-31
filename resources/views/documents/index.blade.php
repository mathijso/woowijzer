<x-layouts.app title="Documenten">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            @if($wooRequest)
                <div class="mb-4">
                    <a href="{{ route('woo-requests.show', $wooRequest) }}"
                       class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                        <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Terug naar WOO-verzoek
                    </a>
                </div>
            @endif
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Documenten</h1>
                    @if($wooRequest)
                        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                            Voor: {{ $wooRequest->title }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Filters and Sorting --}}
        <div class="p-4 mb-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
            <form method="GET" class="flex flex-wrap gap-4">
                @if($wooRequest)
                    <input type="hidden" name="woo_request_id" value="{{ $wooRequest->uuid }}">
                @endif

                <div class="flex-1 min-w-64">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Zoek op bestandsnaam..."
                           class="block px-4 py-2 w-full rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">
                </div>

                <select name="processed"
                        class="px-4 py-2 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">
                    <option value="">Alle statussen</option>
                    <option value="1" {{ request('processed') === '1' ? 'selected' : '' }}>Verwerkt</option>
                    <option value="0" {{ request('processed') === '0' ? 'selected' : '' }}>Niet verwerkt</option>
                </select>

                @if($wooRequest)
                <select name="sort"
                        class="px-4 py-2 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">
                    <option value="relevance" {{ $sortBy === 'relevance' ? 'selected' : '' }}>Relevantie</option>
                    <option value="date" {{ $sortBy === 'date' ? 'selected' : '' }}>Datum</option>
                    <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Naam</option>
                </select>

                <select name="order"
                        class="px-4 py-2 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">
                    <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Hoog → Laag</option>
                    <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Laag → Hoog</option>
                </select>
                @endif

                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-rijksblauw rounded-lg hover:bg-rijksblauw/90">
                    Filteren
                </button>

                @if(request()->hasAny(['search', 'processed', 'sort', 'order']))
                    <a href="{{ request()->url() }}{{ $wooRequest ? '?woo_request_id=' . $wooRequest->uuid : '' }}"
                       class="px-4 py-2 text-sm font-medium bg-white rounded-lg border text-neutral-700 border-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-600">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Documents List --}}
        <div class="bg-white rounded-xl shadow-sm dark:bg-neutral-800">
            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($documents as $document)
                    <a href="{{ $wooRequest ? route('cases.documents.show', [$wooRequest, $document]) : route('documents.show', $document) }}"
                       class="block p-6 transition hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                        <div class="flex gap-4 items-start">
                            {{-- File Icon --}}
                            <div class="flex-shrink-0">
                                <div class="flex justify-center items-center w-12 h-12 bg-blue-100 rounded-lg dark:bg-blue-900/20">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Document Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex gap-2 items-center">
                                    <h3 class="font-semibold truncate text-neutral-900 dark:text-white">
                                        {{ $document->file_name }}
                                    </h3>
                                    @if($wooRequest && $document->relevance_score !== null)
                                        <span class="px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400" title="Relevantie score">
                                            {{ round($document->relevance_score * 100) }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-3 items-center mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                    <span>{{ $document->getFileSizeFormatted() }}</span>
                                    <span>•</span>
                                    <span>{{ $document->file_type }}</span>
                                    <span>•</span>
                                    <span>{{ $document->created_at->format('d-m-Y H:i') }}</span>
                                </div>
                                @if($document->submission)
                                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                        Door: {{ $document->submission->getSubmitterName() }}
                                    </p>
                                @endif
                            </div>

                            {{-- Status & Actions --}}
                            <div class="flex flex-col gap-2 items-end">
                                @if($document->api_processing_status === 'completed')
                                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                        Verwerkt
                                    </span>
                                @elseif($document->api_processing_status === 'processing')
                                    <span class="flex items-center px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-900/20 dark:text-yellow-400">
                                        <svg class="mr-1 w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
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

                                @if($document->questions->count() > 0)
                                    <span class="flex items-center text-xs text-neutral-600 dark:text-neutral-400">
                                        <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $document->questions->count() }} vraag/vragen
                                    </span>
                                @endif

                                @if($document->hasTimelineEvents())
                                    <span class="flex items-center text-xs text-blue-600 dark:text-blue-400">
                                        <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ count($document->getTimelineEvents()) }} events
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-neutral-900 dark:text-white">Geen documenten gevonden</h3>
                        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                            Er zijn nog geen documenten geüpload voor dit verzoek.
                        </p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($documents->hasPages())
                <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                    {{ $documents->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

