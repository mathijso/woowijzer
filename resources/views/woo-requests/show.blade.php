<x-layouts.app :title="$wooRequest->title">
    <div class="mx-auto max-w-7xl">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 bg-green-50 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                <div class="flex gap-2 items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-6 text-sm text-red-800 bg-red-50 rounded-lg dark:bg-red-900/20 dark:text-red-400">
                <div class="flex gap-2 items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <div class="flex gap-4 items-center">
                    @auth
                        @if(auth()->user()->isCaseManager())
                            <a href="{{ route('cases.index') }}"
                               class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Terug naar cases
                            </a>
                        @else
                            <a href="{{ route('woo-requests.index') }}"
                               class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Terug naar overzicht
                            </a>
                        @endif
                    @else
                        <a href="{{ route('woo-requests.index') }}"
                           class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Terug naar overzicht
                        </a>
                    @endauth
                </div>
            </div>

            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $wooRequest->title }}</h1>
                    <div class="flex gap-4 items-center mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                        <span>Ingediend @if($wooRequest->user) door {{ $wooRequest->user->name }} @endif op {{ $wooRequest->submitted_at?->format('d F Y') ?? $wooRequest->created_at->format('d F Y') }}</span>
                        @if($wooRequest->caseManager)
                            <span>•</span>
                            <span>Case manager: {{ $wooRequest->caseManager->name }}</span>
                        @endif
                    </div>
                </div>
                @php
                    $statusColors = [
                        'submitted' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                        'in_review' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                        'in_progress' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                    ];
                    $statusLabels = config('woo.woo_request_statuses');
                @endphp
                <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $statusColors[$wooRequest->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusLabels[$wooRequest->status] ?? $wooRequest->status }}
                </span>
            </div>

            {{-- Progress Bar (Case Managers Only - Detailed) --}}
            @auth
                @if(auth()->user()->isCaseManager() && $wooRequest->questions->count() > 0)
                    <div class="p-4 mt-4 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-neutral-900 dark:text-white">Voortgang</span>
                            <span class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $progressPercentage }}%</span>
                        </div>
                        <div class="overflow-hidden h-3 rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-blue-600 rounded-full transition-all" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-2 text-xs text-center">
                            <div>
                                <span class="font-semibold text-gray-600 dark:text-gray-400">{{ $questionStats['unanswered'] }}</span>
                                <span class="text-neutral-600 dark:text-neutral-400"> onbeantwoord</span>
                            </div>
                            <div>
                                <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ $questionStats['partially_answered'] }}</span>
                                <span class="text-neutral-600 dark:text-neutral-400"> gedeeltelijk</span>
                            </div>
                            <div>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ $questionStats['answered'] }}</span>
                                <span class="text-neutral-600 dark:text-neutral-400"> beantwoord</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Description --}}
                @if($wooRequest->description)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Beschrijving</h2>
                    <p class="mt-3 whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">{{ $wooRequest->description }}</p>
                </div>
                @endif

                {{-- Progress (Burgers View - Simple) --}}
                @if($wooRequest->questions->count() > 0)
                    @guest
                    <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Voortgang</h2>
                        <div class="mt-4">
                            <div class="flex justify-between items-center text-sm text-neutral-600 dark:text-neutral-400">
                                <span>Beantwoorde vragen</span>
                                <span class="font-semibold">{{ round($wooRequest->progress_percentage) }}%</span>
                            </div>
                            <div class="overflow-hidden mt-2 w-full h-2.5 rounded-full bg-neutral-200 dark:bg-neutral-700">
                                <div class="h-full bg-blue-600 rounded-full transition-all"
                                     style="width: {{ $wooRequest->progress_percentage }}%"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div class="p-3 text-center rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                    <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                                        {{ $wooRequest->questions->count() }}
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">Totaal vragen</div>
                                </div>
                                <div class="p-3 text-center bg-green-50 rounded-lg dark:bg-green-900/20">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        {{ $wooRequest->questions->where('status', 'answered')->count() }}
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">Beantwoord</div>
                                </div>
                                <div class="p-3 text-center bg-red-50 rounded-lg dark:bg-red-900/20">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                        {{ $wooRequest->questions->where('status', 'unanswered')->count() }}
                                    </div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400">Open</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        @if(!auth()->user()->isCaseManager())
                        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Voortgang</h2>
                            <div class="mt-4">
                                <div class="flex justify-between items-center text-sm text-neutral-600 dark:text-neutral-400">
                                    <span>Beantwoorde vragen</span>
                                    <span class="font-semibold">{{ round($wooRequest->progress_percentage) }}%</span>
                                </div>
                                <div class="overflow-hidden mt-2 w-full h-2.5 rounded-full bg-neutral-200 dark:bg-neutral-700">
                                    <div class="h-full bg-blue-600 rounded-full transition-all"
                                         style="width: {{ $wooRequest->progress_percentage }}%"></div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mt-4">
                                    <div class="p-3 text-center rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                        <div class="text-2xl font-bold text-neutral-900 dark:text-white">
                                            {{ $wooRequest->questions->count() }}
                                        </div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Totaal vragen</div>
                                    </div>
                                    <div class="p-3 text-center bg-green-50 rounded-lg dark:bg-green-900/20">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ $wooRequest->questions->where('status', 'answered')->count() }}
                                        </div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Beantwoord</div>
                                    </div>
                                    <div class="p-3 text-center bg-red-50 rounded-lg dark:bg-red-900/20">
                                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                            {{ $wooRequest->questions->where('status', 'unanswered')->count() }}
                                        </div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Open</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endguest
                @endif

                {{-- Questions --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Vragen ({{ $wooRequest->questions->count() }})
                        </h2>
                        @auth
                            @if(auth()->user()->isCaseManager())
                                <form action="{{ route('woo-requests.generate-summaries', $wooRequest) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je samenvattingen voor alle vragen wilt genereren? Dit kan enkele minuten duren.');">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        Genereer samenvattingen
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div class="space-y-3">
                        @forelse($wooRequest->questions as $question)
                            <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex gap-4 items-start">
                                            <div class="flex flex-shrink-0 justify-center items-center w-8 h-8 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                                                {{ $question->order }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $question->question_text }}</p>
                                                <div class="flex gap-2 items-center mt-2">
                                                    @php
                                                        $questionStatusColors = [
                                                            'unanswered' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                            'partially_answered' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                                                            'answered' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                                        ];
                                                        $questionStatusLabels = config('woo.question_statuses');
                                                    @endphp
                                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $questionStatusColors[$question->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                        {{ $questionStatusLabels[$question->status] ?? $question->status }}
                                                    </span>
                                                    @if($question->documents->count() > 0)
                                                        <span class="text-xs text-neutral-600 dark:text-neutral-400">
                                                            {{ $question->documents->count() }} document(en) gekoppeld
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($question->ai_summary)
                                                    <div class="p-3 mt-3 text-xs bg-blue-50 rounded-lg text-neutral-700 dark:bg-blue-900/10 dark:text-neutral-300">
                                                        <strong class="block font-semibold text-blue-900 dark:text-blue-200">AI Samenvatting:</strong>
                                                        <div class="mt-1 whitespace-pre-wrap">{{ $question->ai_summary }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
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
                <div class="p-6 bg-white rounded-xl border-2 border-blue-100 shadow-sm dark:bg-neutral-800 dark:border-blue-900/50">
                    <div class="flex justify-between items-start mb-4">
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
                        <div class="p-4 bg-blue-50 rounded-lg dark:bg-blue-900/10">
                            <p class="text-sm leading-relaxed text-neutral-900 dark:text-neutral-100">
                                {{ $wooRequest->caseDecision->summary_b1 }}
                            </p>
                        </div>

                        @if($wooRequest->caseDecision->getKeyReasons())
                        <div>
                            <h3 class="mb-2 text-sm font-semibold text-neutral-900 dark:text-white">Belangrijkste redenen</h3>
                            <ul class="space-y-2">
                                @foreach($wooRequest->caseDecision->getKeyReasons() as $reason)
                                <li class="flex gap-2 items-start text-sm text-neutral-700 dark:text-neutral-300">
                                    <svg class="flex-shrink-0 mt-0.5 w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
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
                            <h3 class="mb-3 text-sm font-semibold text-neutral-900 dark:text-white">Proces overzicht</h3>
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
                    <div class="flex justify-between items-start mb-4">
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
                                <div class="flex justify-center items-center w-8 h-8 bg-blue-100 rounded-full dark:bg-blue-900/20">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
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
                    @endif
                </div>
                @endif

                {{-- Documents --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Documenten ({{ $wooRequest->documents->count() }})
                        </h2>
                        @auth
                            @if(auth()->user()->isCaseManager())
                                <form action="{{ route('woo-requests.auto-link-documents', $wooRequest) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        Auto-link documenten
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('documents.index', ['woo_request_id' => $wooRequest->id]) }}"
                                   class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    Alle documenten →
                                </a>
                            @endif
                        @else
                            <a href="{{ route('documents.index', ['woo_request_id' => $wooRequest->id]) }}"
                               class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                Alle documenten →
                            </a>
                        @endauth
                    </div>

                    <div class="space-y-3">
                        @forelse($wooRequest->documents->take(5) as $document)
                            <a href="{{ route('documents.show', $document) }}"
                               class="flex gap-3 items-center p-3 rounded-lg transition bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate text-neutral-900 dark:text-white">{{ $document->file_name }}</p>
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

                {{-- Internal Requests (Case Managers Only) --}}
                @auth
                    @if(auth()->user()->isCaseManager())
                        <div id="internal-requests-section" class="bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    Document Upload Verzoeken
                                </h2>
                                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                    Vraag collega's om relevante documenten via een beveiligde uploadlink
                                </p>
                            </div>

                            {{-- New Request Form --}}
                            <div id="new-request-form" class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                                <h3 class="mb-4 text-sm font-semibold text-neutral-900 dark:text-white">Nieuw upload verzoek</h3>
                                <form action="{{ route('internal-requests.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="woo_request_id" value="{{ $wooRequest->id }}">
                                    <input type="hidden" name="referer" value="woo-requests.show">

                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label for="colleague_email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                                Email collega <span class="text-red-600">*</span>
                                            </label>
                                            <input type="email"
                                                   name="colleague_email"
                                                   id="colleague_email"
                                                   required
                                                   value="{{ old('colleague_email') }}"
                                                   class="block px-3 py-2 mt-1 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                                   placeholder="collega@overheid.nl">
                                            @error('colleague_email')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="colleague_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                                Naam collega (optioneel)
                                            </label>
                                            <input type="text"
                                                   name="colleague_name"
                                                   id="colleague_name"
                                                   value="{{ old('colleague_name') }}"
                                                   class="block px-3 py-2 mt-1 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                                   placeholder="Jan Jansen">
                                            @error('colleague_name')
                                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="description" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                            Toelichting <span class="text-red-600">*</span>
                                        </label>
                                        <textarea name="description"
                                                  id="description"
                                                  required
                                                  rows="3"
                                                  class="block px-3 py-2 mt-1 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                                  placeholder="Beschrijf welke documenten je nodig hebt...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex gap-2 items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Verstuur verzoek
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Existing Requests List --}}
                            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($wooRequest->internalRequests()->latest()->get() as $internalRequest)
                                    <div class="p-6">
                                        <div class="flex gap-4 justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex gap-2 items-center">
                                                    <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                                        {{ $internalRequest->colleague_name ?? $internalRequest->colleague_email }}
                                                    </p>
                                                    @php
                                                        $irStatusColors = [
                                                            'pending' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                            'submitted' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                                                            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                                            'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                                                        ];
                                                        $irStatusLabels = config('woo.internal_request_statuses', [
                                                            'pending' => 'In afwachting',
                                                            'submitted' => 'Ingediend',
                                                            'completed' => 'Afgerond',
                                                            'expired' => 'Verlopen',
                                                        ]);
                                                    @endphp
                                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $irStatusColors[$internalRequest->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                        {{ $irStatusLabels[$internalRequest->status] ?? $internalRequest->status }}
                                                    </span>
                                                </div>
                                                <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">
                                                    {{ $internalRequest->colleague_email }}
                                                </p>
                                                <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">
                                                    {{ Str::limit($internalRequest->description, 120) }}
                                                </p>
                                                <div class="flex flex-wrap gap-3 items-center mt-2 text-xs text-neutral-600 dark:text-neutral-400">
                                                    <span class="flex gap-1 items-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                        </svg>
                                                        {{ $internalRequest->submissions->count() }} upload(s)
                                                    </span>
                                                    <span class="flex gap-1 items-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        @if($internalRequest->isExpired())
                                                            <span class="text-red-600 dark:text-red-400">Verlopen</span>
                                                        @else
                                                            Verloopt {{ $internalRequest->token_expires_at->diffForHumans() }}
                                                        @endif
                                                    </span>
                                                    <span>Verstuurd {{ $internalRequest->sent_at->format('d-m-Y H:i') }}</span>
                                                </div>

                                                {{-- Upload Link Display --}}
                                                <div class="mt-3">
                                                    <div class="flex gap-2 items-center">
                                                        <input type="text"
                                                               readonly
                                                               value="{{ route('upload.show', $internalRequest->upload_token) }}"
                                                               id="upload-link-{{ $internalRequest->id }}"
                                                               class="flex-1 px-3 py-1.5 font-mono text-xs rounded-lg border border-neutral-300 bg-neutral-50 text-neutral-700 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300">
                                                        <button type="button"
                                                                onclick="copyUploadLink({{ $internalRequest->id }})"
                                                                class="inline-flex gap-1 items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            Kopieer
                                                        </button>
                                                        <a href="{{ route('upload.show', $internalRequest->upload_token) }}"
                                                           target="_blank"
                                                           class="inline-flex gap-1 items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                            </svg>
                                                            Open
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Actions Dropdown --}}
                                            <div class="flex flex-col gap-2">
                                                @if(!$internalRequest->isExpired() && $internalRequest->status !== 'completed')
                                                    <form action="{{ route('internal-requests.resend', $internalRequest) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="inline-flex gap-1 items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                            </svg>
                                                            Opnieuw versturen
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($internalRequest->status !== 'completed' && $internalRequest->status !== 'expired')
                                                    <form action="{{ route('internal-requests.complete', $internalRequest) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="inline-flex gap-1 items-center px-3 py-1.5 text-xs font-medium text-green-700 rounded-lg border border-green-300 hover:bg-green-50 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900/20">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Afgerond
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($internalRequest->status !== 'expired' && $internalRequest->status !== 'completed')
                                                    <form action="{{ route('internal-requests.expire', $internalRequest) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="inline-flex gap-1 items-center px-3 py-1.5 text-xs font-medium text-red-700 rounded-lg border border-red-300 hover:bg-red-50 dark:border-red-600 dark:text-red-400 dark:hover:bg-red-900/20">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                            Vervallen
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6">
                                        <p class="text-sm text-center text-neutral-600 dark:text-neutral-400">
                                            Nog geen interne verzoeken verstuurd. Gebruik het formulier hierboven om een verzoek te sturen.
                                        </p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Original Document --}}
                @if($wooRequest->original_file_path)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Origineel verzoek</h3>
                    <a href="{{ Storage::disk('woo-documents')->url($wooRequest->original_file_path) }}"
                       download
                       class="flex gap-2 items-center p-3 mt-3 bg-blue-50 rounded-lg transition hover:bg-blue-100 dark:bg-blue-900/10 dark:hover:bg-blue-900/20">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Download PDF</span>
                    </a>
                </div>
                @endif

                {{-- Aanvrager Info --}}
                @if($wooRequest->user)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Aanvrager</h3>
                    <div class="flex gap-3 items-center mt-3">
                        <div class="flex justify-center items-center w-10 h-10 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                            {{ $wooRequest->user->initials() }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->user->name }}
                            </p>
                            <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                {{ $wooRequest->user->email }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Actions (Case Managers Only) --}}
                @auth
                    @if(auth()->user()->isCaseManager())
                        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Acties</h3>
                            <div class="mt-4 space-y-3">


                                {{-- Status Dropdown --}}
                                <div>
                                    <label for="status-select" class="block mb-1 text-xs font-medium text-neutral-700 dark:text-neutral-300">
                                        Status wijzigen
                                    </label>
                                    <form action="{{ route('woo-requests.update-status', $wooRequest) }}" method="POST" id="status-form">
                                        @csrf
                                        <select name="status"
                                                id="status-select"
                                                onchange="this.form.submit()"
                                                class="block px-3 py-2 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                            @foreach(config('woo.woo_request_statuses') as $key => $label)
                                                <option value="{{ $key }}" {{ $wooRequest->status === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>

                                <a href="{{ route('woo-requests.generate-report', $wooRequest) }}" class="inline-block px-4 py-2 w-full text-sm font-medium text-center rounded-lg border border-neutral-300 text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                    Genereer rapport
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth

                {{-- Timeline --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="mb-4 text-sm font-semibold text-neutral-900 dark:text-white">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex justify-center items-center w-8 h-8 bg-blue-100 rounded-full dark:bg-blue-900/20">
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
                                <div class="flex justify-center items-center w-8 h-8 bg-blue-100 rounded-full dark:bg-blue-900/20">
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
                            <div class="flex justify-center items-center w-8 h-8 bg-green-100 rounded-full dark:bg-green-900/20">
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
                    <h3 class="mb-4 text-sm font-semibold text-neutral-900 dark:text-white">Statistieken</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Vragen</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->questions->count() }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Documenten</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->documents->count() }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Uploads</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->submissions->count() }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Dagen actief</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->created_at->diffInDays(now()) }}
                            </dd>
                        </div>
                        @auth
                            @if(auth()->user()->isCaseManager())
                                <div class="flex justify-between items-center">
                                    <dt class="text-sm text-neutral-600 dark:text-neutral-400">Interne verzoeken</dt>
                                    <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                        {{ $wooRequest->internalRequests->count() }}
                                    </dd>
                                </div>
                            @endif
                        @endauth
                    </dl>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyUploadLink(requestId) {
            const input = document.getElementById('upload-link-' + requestId);
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(input.value).then(function() {
                // Show temporary success feedback
                const button = event.target.closest('button');
                const originalHtml = button.innerHTML;
                button.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Gekopieerd!';
                button.classList.add('text-green-600', 'border-green-300');

                setTimeout(function() {
                    button.innerHTML = originalHtml;
                    button.classList.remove('text-green-600', 'border-green-300');
                }, 2000);
            }).catch(function(err) {
                console.error('Failed to copy: ', err);
                alert('Kon link niet kopiëren. Probeer handmatig te selecteren en kopiëren.');
            });
        }

        function scrollToInternalRequests() {
            // Try to find the section first, then the form
            const section = document.getElementById('internal-requests-section');
            const form = document.getElementById('new-request-form');
            const target = form || section;

            if (target) {
                // Calculate offset to account for any fixed headers
                const offset = 80; // Adjust this value if needed
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Focus on the first input after scroll completes
                setTimeout(() => {
                    if (form) {
                        const firstInput = form.querySelector('input[type="email"]');
                        if (firstInput) {
                            firstInput.focus();
                        }
                    }
                }, 600);
            }
        }

    </script>
    @endpush
</x-layouts.app>
