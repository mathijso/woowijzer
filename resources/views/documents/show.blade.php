<x-layouts.app :title="$document->file_name">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <a href="{{ route('woo-requests.show.tab', [$wooRequest ?? $document->wooRequest, 'documents']) }}"
                   class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Terug naar documenten
                </a>
            </div>

            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $document->file_name }}</h1>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        Geüpload op {{ $document->created_at->format('d F Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('cases.documents.download', [$wooRequest ?? $document->wooRequest, $document]) }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-rijksblauw hover:bg-blue-700">
                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- API Processing Status --}}
                @if($document->api_processing_status === 'processing' || $document->api_processing_status === 'pending')
                    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 dark:bg-yellow-900/10 dark:border-yellow-800">
                        <div class="flex gap-3 items-start">
                            <svg class="flex-shrink-0 mt-0.5 w-5 h-5 text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    Document wordt verwerkt door WOO Insight API
                                </p>
                                <p class="mt-1 text-xs text-yellow-700 dark:text-yellow-300">
                                    OCR extractie, timeline analyse en samenvatting worden gegenereerd...
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($document->api_processing_status === 'failed')
                    <div class="p-4 bg-red-50 rounded-lg border border-red-200 dark:bg-red-900/10 dark:border-red-800">
                        <div class="flex gap-3 items-start">
                            <svg class="flex-shrink-0 mt-0.5 w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                    Verwerking mislukt
                                </p>
                                @if($document->api_processing_error)
                                <p class="mt-1 text-xs text-red-700 dark:text-red-300">
                                    {{ $document->api_processing_error }}
                                </p>
                                @endif
                                <p class="mt-2 text-xs text-red-600 dark:text-red-400">
                                    Het systeem probeert het document automatisch opnieuw te verwerken.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($document->api_processing_status === 'completed')
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200 dark:bg-green-900/10 dark:border-green-800">
                        <div class="flex gap-3 items-center">
                            <svg class="flex-shrink-0 w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                Document succesvol verwerkt door WOO Insight API
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Tabs Navigation --}}
                <div class="bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="border-b border-neutral-200 dark:border-neutral-700">
                        <nav class="flex overflow-x-auto -mb-px" aria-label="Tabs">
                            @php
                                $activeTab = $activeTab ?? 'overview';
                            @endphp
                            <a href="{{ route('cases.documents.show.tab', [$wooRequest ?? $document->wooRequest, $document, 'overview']) }}"
                               class="flex-shrink-0 px-4 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $activeTab === 'overview' ? 'border-blue-500 text-rijksblauw dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                                Overzicht
                            </a>
                            <a href="{{ route('cases.documents.show.tab', [$wooRequest ?? $document->wooRequest, $document, 'summary']) }}"
                               class="flex-shrink-0 px-4 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $activeTab === 'summary' ? 'border-blue-500 text-rijksblauw dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                                Samenvatting
                            </a>
                            @if($document->hasTimelineEvents())
                            <a href="{{ route('cases.documents.show.tab', [$wooRequest ?? $document->wooRequest, $document, 'timeline']) }}"
                               class="flex-shrink-0 px-4 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $activeTab === 'timeline' ? 'border-blue-500 text-rijksblauw dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                                Timeline
                            </a>
                            @endif
                            @if($document->content_markdown)
                            <a href="{{ route('cases.documents.show.tab', [$wooRequest ?? $document->wooRequest, $document, 'content']) }}"
                               class="flex-shrink-0 px-4 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $activeTab === 'content' ? 'border-blue-500 text-rijksblauw dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                                Inhoud
                            </a>
                            @endif
                            <a href="{{ route('cases.documents.show.tab', [$wooRequest ?? $document->wooRequest, $document, 'questions']) }}"
                               class="flex-shrink-0 px-4 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $activeTab === 'questions' ? 'border-blue-500 text-rijksblauw dark:text-blue-400' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-neutral-300' }}">
                                <span class="flex gap-2 items-center">
                                    Vragen
                                    @if($document->questions->count() > 0)
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $activeTab === 'questions' ? 'text-rijksblauw bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400' : 'text-neutral-600 bg-neutral-100 dark:bg-neutral-800 dark:text-neutral-400' }}">
                                            {{ $document->questions->count() }}
                                        </span>
                                    @endif
                                </span>
                            </a>
                        </nav>
                    </div>

                    {{-- Tab Content: Overview --}}
                    @if($activeTab === 'overview')
                    <div id="tab-overview" class="tab-content">
                        <div class="p-6 space-y-6">
                            {{-- AI Summary --}}
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Samenvatting</h2>
                                <div class="mt-3 max-w-none prose prose-sm dark:prose-invert">
                                    @if($document->ai_summary)
                                        <p class="whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">{{ $document->ai_summary }}</p>
                                    @else
                                        <p class="italic text-neutral-500 dark:text-neutral-400">Nog geen samenvatting beschikbaar voor dit document.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Timeline Events --}}
                            @if($document->hasTimelineEvents())
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Timeline Events uit Document</h2>
                                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                    Geëxtraheerd door WOO Insight API
                                </p>
                                <div class="mt-4 space-y-4">
                                    @foreach($document->getTimelineEvents() as $event)
                                        <div class="flex gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                            <div class="flex flex-col items-center pt-1">
                                                <div class="flex justify-center items-center w-8 h-8 bg-blue-100 rounded-full dark:bg-blue-900/20">
                                                    <svg class="w-4 h-4 text-rijksblauw dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                @if(!$loop->last)
                                                <div class="flex-1 mt-2 w-px bg-neutral-200 dark:bg-neutral-700"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <p class="font-medium text-neutral-900 dark:text-white">{{ $event['title'] ?? 'Gebeurtenis' }}</p>
                                                        <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                                            {{ $event['date'] ?? 'Onbekende datum' }}
                                                            @if(isset($event['type']))
                                                                <span class="px-2 py-0.5 ml-2 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
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
                            </div>
                            @endif

                            {{-- Content Preview --}}
                            @if($document->content_markdown)
                            <div>
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Document Inhoud (OCR)</h2>
                                <div class="mt-3 max-w-none prose prose-sm dark:prose-invert">
                                    <div class="overflow-auto p-4 max-h-64 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                        <pre class="text-xs whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">{{ Str::limit($document->content_markdown, 1000) }}</pre>
                                    </div>
                                    <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-400">
                                        <a href="{{ route('cases.documents.show.tab', [$wooRequest ?? $document->wooRequest, $document, 'content']) }}" class="text-rijksblauw hover:text-blue-700 dark:text-blue-400">
                                            Volledige inhoud bekijken →
                                        </a>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Tab Content: Summary --}}
                    @if($activeTab === 'summary')
                    <div id="tab-summary" class="tab-content">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Samenvatting</h2>
                            <div class="mt-3 max-w-none prose prose-sm dark:prose-invert">
                                @if($document->ai_summary)
                                    <p class="whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">{{ $document->ai_summary }}</p>
                                @else
                                    <p class="italic text-neutral-500 dark:text-neutral-400">Nog geen samenvatting beschikbaar voor dit document.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tab Content: Timeline --}}
                    @if($document->hasTimelineEvents() && $activeTab === 'timeline')
                    <div id="tab-timeline" class="tab-content">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Timeline Events uit Document</h2>
                            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                Geëxtraheerd door WOO Insight API
                            </p>
                            <div class="mt-4 space-y-4">
                                @foreach($document->getTimelineEvents() as $event)
                                    <div class="flex gap-3 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                        <div class="flex flex-col items-center pt-1">
                                            <div class="flex justify-center items-center w-8 h-8 bg-blue-100 rounded-full dark:bg-blue-900/20">
                                                <svg class="w-4 h-4 text-rijksblauw dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            @if(!$loop->last)
                                            <div class="flex-1 mt-2 w-px bg-neutral-200 dark:bg-neutral-700"></div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-neutral-900 dark:text-white">{{ $event['title'] ?? 'Gebeurtenis' }}</p>
                                                    <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                                        {{ $event['date'] ?? 'Onbekende datum' }}
                                                        @if(isset($event['type']))
                                                            <span class="px-2 py-0.5 ml-2 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
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
                        </div>
                    </div>
                    @endif

                    {{-- Tab Content: Content --}}
                    @if($document->content_markdown && $activeTab === 'content')
                    <div id="tab-content" class="tab-content">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Document Inhoud (OCR)</h2>
                            <div class="mt-3 max-w-none prose prose-sm dark:prose-invert">
                                <div class="overflow-auto p-4 max-h-96 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                    <pre class="text-xs whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">{{ Str::limit($document->content_markdown, 2000) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tab Content: Questions --}}
                    @if($activeTab === 'questions')
                    <div id="tab-questions" class="tab-content">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    Gekoppelde Vragen
                                </h2>
                            </div>

                            <livewire:document-questions-list :document="$document" />
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- File Info --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Bestandsinformatie</h3>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Bestandsnaam</dt>
                            <dd class="mt-1 text-sm font-medium break-all text-neutral-900 dark:text-white">{{ $document->file_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Bestandsgrootte</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $document->getFileSizeFormatted() }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Type</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $document->file_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Geüpload</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $document->created_at->format('d-m-Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">API Status</dt>
                            <dd class="mt-1">
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
                            </dd>
                        </div>
                        @if($document->processed_at)
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Verwerkt op</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $document->processed_at->format('d-m-Y H:i') }}</dd>
                        </div>
                        @endif
                        @if($document->processing_metadata_json && isset($document->processing_metadata_json['confidence_score']))
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Betrouwbaarheid</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">
                                {{ round($document->processing_metadata_json['confidence_score'] * 100) }}%
                            </dd>
                        </div>
                        @endif
                        @if($document->relevance_score !== null)
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Relevantie Score</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                                    {{ round($document->relevance_score * 100) }}%
                                </span>
                            </dd>
                        </div>
                        @endif
                        @if($document->relevance_explanation)
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Relevantie Uitleg</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-white whitespace-pre-wrap">
                                {{ $document->relevance_explanation }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Submission Info --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Upload Informatie</h3>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Geüpload door</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $document->submission->getSubmitterName() }}
                            </dd>
                        </div>
                        @if($document->submission->submission_notes)
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Notities</dt>
                            <dd class="mt-1 text-sm text-neutral-900 dark:text-white">
                                {{ $document->submission->submission_notes }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- WOO Request Link --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">WOO Verzoek</h3>
                    <a href="{{ route('woo-requests.show', [$wooRequest ?? $document->wooRequest, 'questions']) }}"
                       class="block mt-3 text-sm text-rijksblauw hover:text-blue-700 dark:text-blue-400">
                        {{ ($wooRequest ?? $document->wooRequest)->title }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

