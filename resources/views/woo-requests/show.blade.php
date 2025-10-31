<x-layouts.app :title="$wooRequest->title">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <a href="{{ route('woo-requests.index') }}" 
                   class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Terug naar overzicht
                </a>
            </div>
            
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $wooRequest->title }}</h1>
                    <div class="flex gap-4 items-center mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                        <span>Ingediend op {{ $wooRequest->submitted_at?->format('d F Y') ?? $wooRequest->created_at->format('d F Y') }}</span>
                        @if($wooRequest->caseManager)
                            <span>•</span>
                            <span>Case manager: {{ $wooRequest->caseManager->name }}</span>
                        @endif
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $wooRequest->getStatusBadgeClass() }}">
                    {{ $wooRequest->getStatusLabel() }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Description --}}
                @if($wooRequest->description)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Beschrijving</h2>
                    <p class="mt-3 text-neutral-700 whitespace-pre-wrap dark:text-neutral-300">{{ $wooRequest->description }}</p>
                </div>
                @endif

                {{-- Progress --}}
                @if($wooRequest->questions->count() > 0)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Voortgang</h2>
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm text-neutral-600 dark:text-neutral-400">
                            <span>Beantwoorde vragen</span>
                            <span class="font-semibold">{{ round($wooRequest->progress_percentage) }}%</span>
                        </div>
                        <div class="overflow-hidden mt-2 w-full bg-neutral-200 rounded-full h-2.5 dark:bg-neutral-700">
                            <div class="h-full bg-rijksblauw rounded-full transition-all" 
                                 style="width: {{ $wooRequest->progress_percentage }}%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="p-3 text-center rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                                    {{ $wooRequest->questions->count() }}
                                </div>
                                <div class="text-xs text-neutral-600 dark:text-neutral-400">Totaal vragen</div>
                            </div>
                            <div class="p-3 text-center rounded-lg bg-green-50 dark:bg-green-900/20">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ $wooRequest->questions->where('status', 'answered')->count() }}
                                </div>
                                <div class="text-xs text-neutral-600 dark:text-neutral-400">Beantwoord</div>
                            </div>
                            <div class="p-3 text-center rounded-lg bg-red-50 dark:bg-red-900/20">
                                <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                    {{ $wooRequest->questions->where('status', 'unanswered')->count() }}
                                </div>
                                <div class="text-xs text-neutral-600 dark:text-neutral-400">Open</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Questions --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Vragen ({{ $wooRequest->questions->count() }})
                        </h2>
                    </div>

                    <div class="space-y-3">
                        @forelse($wooRequest->questions as $question)
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm text-neutral-900 dark:text-white">{{ $question->question_text }}</p>
                                        @if($question->ai_summary)
                                            <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-400">
                                                <strong>Samenvatting:</strong> {{ Str::limit($question->ai_summary, 200) }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 ml-4 text-xs font-medium rounded-full whitespace-nowrap {{ $question->getStatusBadgeClass() }}">
                                        {{ $question->getStatusLabel() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-sm text-center text-neutral-600 dark:text-neutral-400">
                                Nog geen vragen geëxtraheerd uit het document
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Decision Overview (B1 Summary) --}}
                @if($wooRequest->hasDecision())
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800 border-2 border-blue-100 dark:border-blue-900/50">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Besluitoverzicht</h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                Samenvatting in B1-Nederlands • Gegenereerd door WOO Insight API
                            </p>
                        </div>
                        @if($wooRequest->caseDecision)
                        <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                            {{ $wooRequest->caseDecision->document_count }} documenten
                        </span>
                        @endif
                    </div>
                    
                    @if($wooRequest->caseDecision)
                    <div class="space-y-4">
                        <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/10">
                            <p class="text-sm text-neutral-900 dark:text-neutral-100 leading-relaxed">
                                {{ $wooRequest->caseDecision->summary_b1 }}
                            </p>
                        </div>

                        @if($wooRequest->caseDecision->getKeyReasons())
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-2">Belangrijkste redenen</h3>
                            <ul class="space-y-2">
                                @foreach($wooRequest->caseDecision->getKeyReasons() as $reason)
                                <li class="flex items-start gap-2 text-sm text-neutral-700 dark:text-neutral-300">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $reason }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($wooRequest->caseDecision->getProcessOutline())
                        <div>
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Proces overzicht</h3>
                            <div class="space-y-3">
                                @foreach($wooRequest->caseDecision->getProcessOutline() as $phase)
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-20 text-xs font-medium text-neutral-600 dark:text-neutral-400">
                                        {{ $phase['when'] ?? '' }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $phase['phase'] ?? '' }}</p>
                                        <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ $phase['what'] ?? '' }}</p>
                                        @if(isset($phase['who']) && !empty($phase['who']))
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($phase['who'] as $person)
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                                {{ $person }}
                                            </span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                {{-- Aggregated Timeline --}}
                @if($wooRequest->hasTimeline())
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Complete Timeline</h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                Geaggregeerd uit alle documenten • WOO Insight API
                            </p>
                        </div>
                        @if($wooRequest->caseTimeline)
                        <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                            {{ $wooRequest->caseTimeline->getEventCount() }} events
                        </span>
                        @endif
                    </div>
                    
                    @if($wooRequest->caseTimeline && $wooRequest->caseTimeline->hasEvents())
                    <div class="mt-4 space-y-4">
                        @foreach($wooRequest->caseTimeline->getEvents() as $event)
                        <div class="flex gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <div class="flex flex-col items-center pt-1">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/20">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @if(!$loop->last)
                                <div class="w-px flex-1 mt-2 bg-neutral-200 dark:bg-neutral-700"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-neutral-900 dark:text-white">{{ $event['title'] ?? 'Gebeurtenis' }}</p>
                                        <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                            {{ $event['date'] ?? 'Onbekende datum' }}
                                            @if(isset($event['type']))
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                                    {{ $event['type'] }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    @if(isset($event['confidence']))
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ round($event['confidence'] * 100) }}%
                                    </span>
                                    @endif
                                </div>
                                @if(isset($event['summary']))
                                <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-300">{{ $event['summary'] }}</p>
                                @endif
                                @if(isset($event['actors']) && !empty($event['actors']))
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($event['actors'] as $actor)
                                    <span class="px-2 py-1 text-xs rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                        {{ $actor }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- Documents --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Documenten ({{ $wooRequest->documents->count() }})
                        </h2>
                        <a href="{{ route('documents.index', ['woo_request_id' => $wooRequest->id]) }}" 
                           class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            Alle documenten →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($wooRequest->documents->take(5) as $document)
                            <a href="{{ route('documents.show', $document) }}" 
                               class="flex items-center gap-3 p-3 rounded-lg transition bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 truncate dark:text-white">{{ $document->file_name }}</p>
                                    <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                        {{ $document->getFileSizeFormatted() }} • {{ $document->created_at->format('d-m-Y') }}
                                    </p>
                                </div>
                                @if($document->api_processing_status === 'completed')
                                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                        Verwerkt
                                    </span>
                                @elseif($document->api_processing_status === 'processing')
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-900/20 dark:text-yellow-400">
                                        Bezig...
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
                            </a>
                        @empty
                            <p class="py-4 text-sm text-center text-neutral-600 dark:text-neutral-400">
                                Nog geen documenten geüpload
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Original Document --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Origineel verzoek</h3>
                    <a href="{{ Storage::disk('woo-documents')->url($wooRequest->original_file_path) }}" 
                       download
                       class="flex items-center gap-2 p-3 mt-3 rounded-lg transition bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/10 dark:hover:bg-blue-900/20">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Download PDF</span>
                    </a>
                </div>

                {{-- Timeline --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/20">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="w-px h-full bg-neutral-200 dark:bg-neutral-700"></div>
                            </div>
                            <div class="pb-4">
                                <p class="text-xs font-medium text-neutral-900 dark:text-white">Ingediend</p>
                                <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                    {{ $wooRequest->submitted_at?->format('d-m-Y H:i') ?? $wooRequest->created_at->format('d-m-Y H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($wooRequest->caseManager)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/20">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @if($wooRequest->status !== 'completed')
                                <div class="w-px h-full bg-neutral-200 dark:bg-neutral-700"></div>
                                @endif
                            </div>
                            <div class="{{ $wooRequest->status !== 'completed' ? 'pb-4' : '' }}">
                                <p class="text-xs font-medium text-neutral-900 dark:text-white">Toegewezen</p>
                                <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                    Aan {{ $wooRequest->caseManager->name }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($wooRequest->status === 'completed')
                        <div class="flex gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/20">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-neutral-900 dark:text-white">Afgerond</p>
                                <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                    {{ $wooRequest->completed_at?->format('d-m-Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4">Statistieken</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Totaal documenten</dt>
                            <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">{{ $wooRequest->documents->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Interne verzoeken</dt>
                            <dd class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white">{{ $wooRequest->internalRequests->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
