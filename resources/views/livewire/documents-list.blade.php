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

    {{-- Documents Table --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm dark:bg-neutral-800">
        <table class="w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900">
                <tr>

                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-center uppercase text-neutral-700 dark:text-neutral-300">
                        Score
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-xs font-medium tracking-wider text-left uppercase text-neutral-700 dark:text-neutral-300">
                        Document
                    </th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-center uppercase text-neutral-700 dark:text-neutral-300">
                        Status
                    </th>


                    <th scope="col" class="relative px-4 py-3">
                        <span class="sr-only">Acties</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200 dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse($documents as $document)
                    <tr x-data="{ expanded: false }"
                        class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                        {{-- Relevance Score --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            @if($document->relevance_score !== null)
                                <div class="flex justify-center">
                                    <span
                                        class="px-3 py-1.5 text-sm font-bold rounded-lg {{ $document->relevance_score >= 0.7 ? 'text-green-700 bg-green-50 border-green-300 dark:bg-green-900/30 dark:text-green-400 dark:border-green-700' : ($document->relevance_score >= 0.4 ? 'text-yellow-700 bg-yellow-50 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-700' : 'text-red-700 bg-red-50 border-red-300 dark:bg-red-900/30 dark:text-red-400 dark:border-red-700') }}"
                                        title="Relevantie score: {{ round($document->relevance_score * 100) }}%">
                                        {{ round($document->relevance_score * 100) }}%
                                    </span>
                                </div>
                            @elseif($document->api_processing_status === 'completed')
                                <span
                                    class="px-2 py-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400"
                                    title="Geen relevance score beschikbaar">
                                    -
                                </span>
                            @else
                                <span class="text-xs text-neutral-400">-</span>
                            @endif
                        </td>

                        {{-- Document Name --}}
                        <td class="px-4 py-4 whitespace-normal" style="max-width: 500px;">
                            <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
                               class="flex gap-2 items-center group">

                                <span class="text-sm font-medium break-all text-neutral-900 group-hover:text-rijksblauw dark:text-white dark:group-hover:text-blue-400"
                                      style="word-break:break-all; display:inline-block; max-width:500px; overflow-wrap:break-word; white-space:normal;">

                                    {{ $document->file_name }}

                                </span>
                            </a>
                        </td>


                        {{-- Status --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            @if($document->api_processing_status === 'completed')
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                    Verwerkt
                                </span>
                            @elseif($document->api_processing_status === 'processing')
                                <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-900/20 dark:text-yellow-400">
                                    Verwerken...
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
                        </td>



                        {{-- Actions --}}
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
                               class="inline-flex items-center text-neutral-400 hover:text-rijksblauw dark:hover:text-blue-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    {{-- Expandable Row: Relevance Explanation --}}
                    @if($document->relevance_explanation)
                        <tr x-show="expanded"
                            x-cloak
                            class="bg-blue-50 dark:bg-blue-900/10">
                            <td colspan="8" class="px-4 py-4">
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800">
                                    <div class="flex gap-2 items-start">
                                        <svg class="flex-shrink-0 mt-0.5 w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="mb-1 text-xs font-medium text-blue-900 dark:text-blue-300">Relevantie uitleg:</p>
                                            <p class="text-sm whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">
                                                {{ $document->relevance_explanation }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-sm text-center text-neutral-600 dark:text-neutral-400">
                            @if($search)
                                Geen documenten gevonden voor "{{ $search }}"
                            @else
                                Nog geen documenten geüpload
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($documents->hasPages())
        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    @endif
</div>
