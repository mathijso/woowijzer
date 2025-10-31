<x-layouts.app :title="$wooRequest->title">
    <div class="mx-auto max-w-7xl">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 mb-6 p-4 rounded-lg text-green-800 dark:text-green-400 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 mb-6 p-4 rounded-lg text-red-800 dark:text-red-400 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Document Processing Status Banner --}}
        @if($wooRequest->original_file_path)
            @if($wooRequest->isPendingProcessing())
                <div class="bg-gray-50 dark:bg-gray-900/20 mb-6 p-4 rounded-lg text-sm">
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <span>Document staat in de wachtrij voor verwerking...</span>
                    </div>
                </div>
                <meta http-equiv="refresh" content="5">
            @elseif($wooRequest->isProcessing())
                <div class="bg-blue-50 dark:bg-blue-900/20 mb-6 p-4 rounded-lg text-sm">
                    <div class="flex items-center gap-2 text-blue-700 dark:text-blue-400">
                        <svg class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Document wordt momenteel verwerkt. Vragen worden automatisch geëxtraheerd...</span>
                    </div>
                </div>
                <meta http-equiv="refresh" content="5">
            @elseif($wooRequest->hasProcessingFailed())
                <div class="bg-red-50 dark:bg-red-900/20 mb-6 p-4 rounded-lg text-sm">
                    <div class="flex items-start gap-2 text-red-700 dark:text-red-400">
                        <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <div class="font-medium">Documentverwerking is mislukt</div>
                            @if($wooRequest->processing_error)
                                <div class="mt-1 text-xs">{{ $wooRequest->processing_error }}</div>
                            @endif
                            @auth
                                @if(auth()->user()->isCaseManager())
                                    <form action="{{ route('woo-requests.retry-processing', $wooRequest) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="font-medium text-red-700 hover:text-red-600 dark:hover:text-red-300 dark:text-red-400 text-xs underline">
                                            Opnieuw proberen
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->isCaseManager())
                            <a href="{{ route('cases.index') }}"
                               class="inline-flex items-center text-neutral-600 hover:text-neutral-900 dark:hover:text-white dark:text-neutral-400 text-sm">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Terug naar cases
                            </a>
                        @else
                            <a href="{{ route('woo-requests.index') }}"
                               class="inline-flex items-center text-neutral-600 hover:text-neutral-900 dark:hover:text-white dark:text-neutral-400 text-sm">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Terug naar overzicht
                            </a>
                        @endif
                    @else
                        <a href="{{ route('woo-requests.index') }}"
                           class="inline-flex items-center text-neutral-600 hover:text-neutral-900 dark:hover:text-white dark:text-neutral-400 text-sm">
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
                    <h1 class="font-bold text-neutral-900 dark:text-white text-2xl">{{ $wooRequest->title }}</h1>
                    <div class="flex items-center gap-4 mt-2 text-neutral-600 dark:text-neutral-400 text-sm">
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
                    <div class="bg-white dark:bg-neutral-800 shadow-sm mt-4 p-4 rounded-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-neutral-900 dark:text-white text-sm">Voortgang</span>
                            <span class="font-semibold text-neutral-900 dark:text-white text-sm">{{ $progressPercentage }}%</span>
                        </div>
                        <div class="bg-neutral-200 dark:bg-neutral-700 rounded-full h-3 overflow-hidden">
                            <div class="bg-rijksblauw rounded-full h-full transition-all" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="gap-2 grid grid-cols-3 mt-2 text-xs text-center">
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

        <div class="gap-6 grid lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Description --}}
                @if($wooRequest->description)
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Beschrijving</h2>
                    <p class="mt-3 text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">{{ $wooRequest->description }}</p>
                </div>
                @endif

                {{-- Progress (Burgers View - Simple) --}}
                @if($wooRequest->questions->count() > 0)
                    @guest
                    <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                        <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Voortgang</h2>
                        <div class="mt-4">
                            <div class="flex justify-between items-center text-neutral-600 dark:text-neutral-400 text-sm">
                                <span>Beantwoorde vragen</span>
                                <span class="font-semibold">{{ round($wooRequest->progress_percentage) }}%</span>
                            </div>
                            <div class="bg-neutral-200 dark:bg-neutral-700 mt-2 rounded-full w-full h-2.5 overflow-hidden">
                                <div class="bg-rijksblauw rounded-full h-full transition-all"
                                     style="width: {{ $wooRequest->progress_percentage }}%"></div>
                            </div>
                            <div class="gap-4 grid grid-cols-3 mt-4">
                                <div class="bg-neutral-50 dark:bg-neutral-900 p-3 rounded-lg text-center">
                                    <div class="font-bold text-neutral-900 dark:text-white text-2xl">
                                        {{ $wooRequest->questions->count() }}
                                    </div>
                                    <div class="text-neutral-600 dark:text-neutral-400 text-xs">Totaal vragen</div>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg text-center">
                                    <div class="font-bold text-green-600 dark:text-green-400 text-2xl">
                                        {{ $wooRequest->questions->where('status', 'answered')->count() }}
                                    </div>
                                    <div class="text-neutral-600 dark:text-neutral-400 text-xs">Beantwoord</div>
                                </div>
                                <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-center">
                                    <div class="font-bold text-red-600 dark:text-red-400 text-2xl">
                                        {{ $wooRequest->questions->where('status', 'unanswered')->count() }}
                                    </div>
                                    <div class="text-neutral-600 dark:text-neutral-400 text-xs">Open</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        @if(!auth()->user()->isCaseManager())
                        <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                            <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Voortgang</h2>
                            <div class="mt-4">
                                <div class="flex justify-between items-center text-neutral-600 dark:text-neutral-400 text-sm">
                                    <span>Beantwoorde vragen</span>
                                    <span class="font-semibold">{{ round($wooRequest->progress_percentage) }}%</span>
                                </div>
                                <div class="bg-neutral-200 dark:bg-neutral-700 mt-2 rounded-full w-full h-2.5 overflow-hidden">
                                    <div class="bg-rijksblauw rounded-full h-full transition-all"
                                         style="width: {{ $wooRequest->progress_percentage }}%"></div>
                                </div>
                                <div class="gap-4 grid grid-cols-3 mt-4">
                                    <div class="bg-neutral-50 dark:bg-neutral-900 p-3 rounded-lg text-center">
                                        <div class="font-bold text-neutral-900 dark:text-white text-2xl">
                                            {{ $wooRequest->questions->count() }}
                                        </div>
                                        <div class="text-neutral-600 dark:text-neutral-400 text-xs">Totaal vragen</div>
                                    </div>
                                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg text-center">
                                        <div class="font-bold text-green-600 dark:text-green-400 text-2xl">
                                            {{ $wooRequest->questions->where('status', 'answered')->count() }}
                                        </div>
                                        <div class="text-neutral-600 dark:text-neutral-400 text-xs">Beantwoord</div>
                                    </div>
                                    <div class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-center">
                                        <div class="font-bold text-red-600 dark:text-red-400 text-2xl">
                                            {{ $wooRequest->questions->where('status', 'unanswered')->count() }}
                                        </div>
                                        <div class="text-neutral-600 dark:text-neutral-400 text-xs">Open</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endguest
                @endif

                {{-- Questions --}}
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">
                            Vragen ({{ $wooRequest->questions->count() }})
                        </h2>
                        @auth
                            @if(auth()->user()->isCaseManager())
                                <form action="{{ route('woo-requests.generate-summaries', $wooRequest) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je samenvattingen voor alle vragen wilt genereren? Dit kan enkele minuten duren.');">
                                    @csrf
                                    <button type="submit" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm">
                                        Genereer samenvattingen
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div class="space-y-3">
                        @forelse($wooRequest->questions as $question)
                            <a href="{{ route('cases.questions.show', [$wooRequest, $question]) }}"
                               class="block bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 p-4 rounded-lg transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <div class="flex flex-shrink-0 justify-center items-center bg-blue-100 dark:bg-blue-900/20 rounded-full w-8 h-8 font-semibold text-blue-600 dark:text-blue-400 text-sm">
                                                {{ $question->order }}
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-neutral-900 dark:text-white text-sm">{{ $question->question_text }}</p>
                                                <div class="flex items-center gap-2 mt-2">
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
                                                        <span class="text-neutral-600 dark:text-neutral-400 text-xs">
                                                            {{ $question->documents->count() }} document(en) gekoppeld
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($question->ai_summary)
                                                    <div class="bg-blue-50 dark:bg-blue-900/10 mt-3 p-3 rounded-lg text-neutral-700 dark:text-neutral-300 text-xs">
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
                            <p class="py-4 text-neutral-600 dark:text-neutral-400 text-sm text-center">
                                Nog geen vragen geëxtraheerd uit het document
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Decision Overview (B1 Summary) --}}
                @if($wooRequest->hasDecision())
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 border-2 border-blue-100 dark:border-blue-900/50 rounded-xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Besluitoverzicht</h2>
                            <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                                Samenvatting in B1-Nederlands • Gegenereerd door WOO Insight API
                            </p>
                        </div>
                        @if($wooRequest->caseDecision)
                        <span class="bg-blue-100 dark:bg-blue-900/20 px-2 py-1 rounded-full font-medium text-blue-700 dark:text-blue-400 text-xs">
                            {{ $wooRequest->caseDecision->document_count }} documenten
                        </span>
                        @endif
                    </div>

                    @if($wooRequest->caseDecision)
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-lg">
                            <p class="text-neutral-900 dark:text-neutral-100 text-sm leading-relaxed">
                                {{ $wooRequest->caseDecision->summary_b1 }}
                            </p>
                        </div>

                        @if($wooRequest->caseDecision->getKeyReasons())
                        <div>
                            <h3 class="mb-2 font-semibold text-neutral-900 dark:text-white text-sm">Belangrijkste redenen</h3>
                            <ul class="space-y-2">
                                @foreach($wooRequest->caseDecision->getKeyReasons() as $reason)
                                <li class="flex items-start gap-2 text-neutral-700 dark:text-neutral-300 text-sm">
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
                            <h3 class="mb-3 font-semibold text-neutral-900 dark:text-white text-sm">Proces overzicht</h3>
                            <div class="space-y-3">
                                @foreach($wooRequest->caseDecision->getProcessOutline() as $phase)
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-20 font-medium text-neutral-600 dark:text-neutral-400 text-xs">
                                        {{ $phase['when'] ?? '' }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-neutral-900 dark:text-white text-sm">{{ $phase['phase'] ?? '' }}</p>
                                        <p class="text-neutral-600 dark:text-neutral-400 text-sm">{{ $phase['what'] ?? '' }}</p>
                                        @if(isset($phase['who']) && !empty($phase['who']))
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($phase['who'] as $person)
                                            <span class="bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 rounded-full text-neutral-700 dark:text-neutral-300 text-xs">
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
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Complete Timeline</h2>
                            <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                                Geaggregeerd uit alle documenten • WOO Insight API
                            </p>
                        </div>
                        @if($wooRequest->caseTimeline)
                        <span class="bg-blue-100 dark:bg-blue-900/20 px-2 py-1 rounded-full font-medium text-blue-700 dark:text-blue-400 text-xs">
                            {{ $wooRequest->caseTimeline->getEventCount() }} events
                        </span>
                        @endif
                    </div>

                    @if($wooRequest->caseTimeline && $wooRequest->caseTimeline->hasEvents())
                    <div class="space-y-4 mt-4">
                        @foreach($wooRequest->caseTimeline->getEvents() as $event)
                        <div class="flex gap-3 bg-neutral-50 dark:bg-neutral-900 p-4 rounded-lg">
                            <div class="flex flex-col items-center pt-1">
                                <div class="flex justify-center items-center bg-blue-100 dark:bg-blue-900/20 rounded-full w-8 h-8">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @if(!$loop->last)
                                <div class="flex-1 bg-neutral-200 dark:bg-neutral-700 mt-2 w-px"></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-neutral-900 dark:text-white">{{ $event['title'] ?? 'Gebeurtenis' }}</p>
                                        <p class="text-neutral-600 dark:text-neutral-400 text-xs">
                                            {{ $event['date'] ?? 'Onbekende datum' }}
                                            @if(isset($event['type']))
                                                <span class="bg-blue-100 dark:bg-blue-900/20 ml-2 px-2 py-0.5 rounded-full font-medium text-blue-700 dark:text-blue-400 text-xs">
                                                    {{ $event['type'] }}
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    @if(isset($event['confidence']))
                                    <span class="text-neutral-500 dark:text-neutral-400 text-xs">
                                        {{ round($event['confidence'] * 100) }}%
                                    </span>
                                    @endif
                                </div>
                                @if(isset($event['summary']))
                                <p class="mt-2 text-neutral-700 dark:text-neutral-300 text-sm">{{ $event['summary'] }}</p>
                                @endif
                                @if(isset($event['actors']) && !empty($event['actors']))
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($event['actors'] as $actor)
                                    <span class="bg-neutral-100 dark:bg-neutral-800 px-2 py-1 rounded-full text-neutral-700 dark:text-neutral-300 text-xs">
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
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">
                            Documenten ({{ $wooRequest->documents->count() }})
                        </h2>
                        <div class="flex items-center gap-4">
                            @auth
                                @if(auth()->user()->isCaseManager())
                                    <form action="{{ route('woo-requests.auto-link-documents', $wooRequest) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 text-sm">
                                            Auto-link documenten
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="space-y-3">
                        @php
                            $documents = $wooRequest->documents->sortByDesc('created_at')->take(5);
                        @endphp
                        @forelse($documents as $document)
                            <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
                               class="flex items-center gap-3 bg-neutral-50 hover:bg-neutral-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 p-3 rounded-lg transition cursor-pointer">
                                <svg class="flex-shrink-0 w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-neutral-900 dark:text-white text-sm truncate">{{ $document->file_name }}</p>
                                    <p class="text-neutral-600 dark:text-neutral-400 text-xs">
                                        {{ $document->getFileSizeFormatted() }} • {{ $document->created_at->format('d-m-Y H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($document->api_processing_status === 'completed')
                                        <span class="bg-green-100 dark:bg-green-900/20 px-2 py-1 rounded-full font-medium text-green-700 dark:text-green-400 text-xs">
                                            Verwerkt
                                        </span>
                                    @elseif($document->api_processing_status === 'processing')
                                        <span class="bg-yellow-100 dark:bg-yellow-900/20 px-2 py-1 rounded-full font-medium text-yellow-700 dark:text-yellow-400 text-xs">
                                            Thinking...
                                        </span>
                                    @elseif($document->api_processing_status === 'failed')
                                        <span class="bg-red-100 dark:bg-red-900/20 px-2 py-1 rounded-full font-medium text-red-700 dark:text-red-400 text-xs">
                                            Mislukt
                                        </span>
                                    @else
                                        <span class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full font-medium text-gray-700 dark:text-gray-400 text-xs">
                                            In wachtrij
                                        </span>
                                    @endif
                                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @empty
                            <p class="py-4 text-neutral-600 dark:text-neutral-400 text-sm text-center">
                                Nog geen documenten geüpload
                            </p>
                        @endforelse

                        @if($wooRequest->documents->count() > 5)
                            <div class="pt-3 border-neutral-200 dark:border-neutral-700 border-t">
                                <a href="{{ route('cases.documents.index', $wooRequest) }}"
                                   class="flex justify-center items-center gap-2 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/10 dark:hover:bg-blue-900/20 py-3 rounded-lg font-medium text-blue-600 dark:text-blue-400 text-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Alle {{ $wooRequest->documents->count() }} documenten bekijken
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @elseif($wooRequest->documents->count() > 0)
                            <div class="pt-3 border-neutral-200 dark:border-neutral-700 border-t">
                                <a href="{{ route('cases.documents.index', $wooRequest) }}"
                                   class="flex justify-center items-center gap-2 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/10 dark:hover:bg-blue-900/20 py-3 rounded-lg font-medium text-blue-600 dark:text-blue-400 text-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    Alle documenten bekijken
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Internal Requests (Case Managers Only) --}}
                @auth
                    @if(auth()->user()->isCaseManager())
                        <div id="internal-requests-section" class="bg-white dark:bg-neutral-800 shadow-sm rounded-xl">
                            <div class="p-6 border-neutral-200 dark:border-neutral-700 border-b">
                                <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">
                                    Document Upload Verzoeken
                                </h2>
                                <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                                    Vraag collega's om relevante documenten via een beveiligde uploadlink
                                </p>
                            </div>

                            {{-- New Request Form --}}
                            <div id="new-request-form" class="p-6 border-neutral-200 dark:border-neutral-700 border-b">
                                <h3 class="mb-4 font-semibold text-neutral-900 dark:text-white text-sm">Nieuw upload verzoek</h3>
                                <form action="{{ route('internal-requests.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="woo_request_id" value="{{ $wooRequest->id }}">
                                    <input type="hidden" name="referer" value="woo-requests.show">

                                    <div class="gap-4 grid md:grid-cols-2">
                                        <div>
                                            <label for="colleague_email" class="block font-medium text-neutral-700 dark:text-neutral-300 text-sm">
                                                Email collega <span class="text-red-600">*</span>
                                            </label>
                                            <input type="email"
                                                   name="colleague_email"
                                                   id="colleague_email"
                                                   required
                                                   value="{{ old('colleague_email') }}"
                                                   class="block dark:bg-neutral-900 shadow-sm mt-1 px-3 py-2 border border-neutral-300 focus:border-blue-500 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full dark:text-white text-sm"
                                                   placeholder="collega@overheid.nl">
                                            @error('colleague_email')
                                                <p class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="colleague_name" class="block font-medium text-neutral-700 dark:text-neutral-300 text-sm">
                                                Naam collega (optioneel)
                                            </label>
                                            <input type="text"
                                                   name="colleague_name"
                                                   id="colleague_name"
                                                   value="{{ old('colleague_name') }}"
                                                   class="block dark:bg-neutral-900 shadow-sm mt-1 px-3 py-2 border border-neutral-300 focus:border-blue-500 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full dark:text-white text-sm"
                                                   placeholder="Jan Jansen">
                                            @error('colleague_name')
                                                <p class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="description" class="block font-medium text-neutral-700 dark:text-neutral-300 text-sm">
                                            Toelichting <span class="text-red-600">*</span>
                                        </label>
                                        <textarea name="description"
                                                  id="description"
                                                  required
                                                  rows="3"
                                                  class="block dark:bg-neutral-900 shadow-sm mt-1 px-3 py-2 border border-neutral-300 focus:border-blue-500 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full dark:text-white text-sm"
                                                  placeholder="Beschrijf welke documenten je nodig hebt...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <p class="mt-1 text-red-600 dark:text-red-400 text-xs">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 bg-rijksblauw hover:bg-blue-700 px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium text-white text-sm">
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
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-medium text-neutral-900 dark:text-white text-sm">
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
                                                <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-xs">
                                                    {{ $internalRequest->colleague_email }}
                                                </p>
                                                <p class="mt-1 text-neutral-700 dark:text-neutral-300 text-sm">
                                                    {{ Str::limit($internalRequest->description, 120) }}
                                                </p>
                                                <div class="flex flex-wrap items-center gap-3 mt-2 text-neutral-600 dark:text-neutral-400 text-xs">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                        </svg>
                                                        {{ $internalRequest->submissions->count() }} upload(s)
                                                    </span>
                                                    <span class="flex items-center gap-1">
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
                                                    <div class="flex items-center gap-2">
                                                        <input type="text"
                                                               readonly
                                                               value="{{ route('upload.show', $internalRequest->upload_token) }}"
                                                               id="upload-link-{{ $internalRequest->id }}"
                                                               class="flex-1 bg-neutral-50 dark:bg-neutral-900 px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-lg font-mono text-neutral-700 dark:text-neutral-300 text-xs">
                                                        <button type="button"
                                                                onclick="copyUploadLink({{ $internalRequest->id }}, this)"
                                                                class="inline-flex items-center gap-1 hover:bg-neutral-50 dark:hover:bg-neutral-700 px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-neutral-700 dark:text-neutral-300 text-xs transition-colors"
                                                                id="copy-btn-{{ $internalRequest->id }}">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            <span class="copy-text">Kopieer</span>
                                                        </button>
                                                        <a href="{{ route('upload.show', $internalRequest->upload_token) }}"
                                                           target="_blank"
                                                           class="inline-flex items-center gap-1 hover:bg-neutral-50 dark:hover:bg-neutral-700 px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-neutral-700 dark:text-neutral-300 text-xs">
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
                                                                class="inline-flex items-center gap-1 hover:bg-neutral-50 dark:hover:bg-neutral-700 px-3 py-1.5 border border-neutral-300 dark:border-neutral-600 rounded-lg font-medium text-neutral-700 dark:text-neutral-300 text-xs">
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
                                                                class="inline-flex items-center gap-1 hover:bg-green-50 dark:hover:bg-green-900/20 px-3 py-1.5 border border-green-300 dark:border-green-600 rounded-lg font-medium text-green-700 dark:text-green-400 text-xs">
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
                                                                class="inline-flex items-center gap-1 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-1.5 border border-red-300 dark:border-red-600 rounded-lg font-medium text-red-700 dark:text-red-400 text-xs">
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
                                        <p class="text-neutral-600 dark:text-neutral-400 text-sm text-center">
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
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <h3 class="font-semibold text-neutral-900 dark:text-white text-sm">Origineel verzoek</h3>
                    <a href="{{ route('woo-requests.download-document', $wooRequest) }}"
                       download
                       class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/10 dark:hover:bg-blue-900/20 mt-3 p-3 rounded-lg transition">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium text-blue-600 dark:text-blue-400 text-sm">Download PDF</span>
                    </a>
                </div>
                @endif

                {{-- Aanvrager Info --}}
                @if($wooRequest->user)
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <h3 class="font-semibold text-neutral-900 dark:text-white text-sm">Aanvrager</h3>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex justify-center items-center bg-blue-100 dark:bg-blue-900/20 rounded-full w-10 h-10 font-semibold text-blue-600 dark:text-blue-400 text-sm">
                            {{ $wooRequest->user->initials() }}
                        </div>
                        <div>
                            <p class="font-medium text-neutral-900 dark:text-white text-sm">
                                {{ $wooRequest->user->name }}
                            </p>
                            <p class="text-neutral-600 dark:text-neutral-400 text-xs">
                                {{ $wooRequest->user->email }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Actions (Case Managers Only) --}}
                @auth
                    @if(auth()->user()->isCaseManager())
                        <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                            <h3 class="font-semibold text-neutral-900 dark:text-white text-sm">Acties</h3>
                            <div class="space-y-3 mt-4">
                                {{-- Case Assignment --}}
                                @if(!$wooRequest->case_manager_id)
                                    {{-- Not assigned - show pickup and assign options --}}
                                    <div>
                                        <label class="block mb-1 font-medium text-neutral-700 dark:text-neutral-300 text-xs">
                                            Case toewijzing
                                        </label>
                                        <div class="flex gap-2">
                                            <form action="{{ route('woo-requests.pickup', $wooRequest) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex justify-center items-center bg-rijksblauw hover:bg-rijksblauw/90 px-4 py-2 rounded-lg w-full font-medium text-white text-sm transition-colors">
                                                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    Case oppakken
                                                </button>
                                            </form>
                                            <form action="{{ route('woo-requests.assign-case-manager', $wooRequest) }}" method="POST" class="flex-1">
                                                @csrf
                                                <select name="case_manager_id"
                                                        onchange="this.form.submit()"
                                                        class="block dark:bg-neutral-900 shadow-sm px-3 py-2 border border-neutral-300 focus:border-blue-500 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full dark:text-white text-sm">
                                                    <option value="">Toewijzen aan...</option>
                                                    @foreach($caseManagers as $manager)
                                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($wooRequest->case_manager_id === auth()->id())
                                    {{-- Assigned to current user - show reassign option --}}
                                    <div>
                                        <label class="block mb-1 font-medium text-neutral-700 dark:text-neutral-300 text-xs">
                                            Case herverdelen
                                        </label>
                                        <form action="{{ route('woo-requests.assign-case-manager', $wooRequest) }}" method="POST">
                                            @csrf
                                            <select name="case_manager_id"
                                                    onchange="this.form.submit()"
                                                    class="block dark:bg-neutral-900 shadow-sm px-3 py-2 border border-neutral-300 focus:border-blue-500 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full dark:text-white text-sm">
                                                <option value="{{ $wooRequest->case_manager_id }}">Toegewezen aan: {{ $wooRequest->caseManager->name }}</option>
                                                <option value="">---</option>
                                                @foreach($caseManagers->where('id', '!=', auth()->id()) as $manager)
                                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                                @endforeach
                                                <option value="">Niet toegewezen</option>
                                            </select>
                                        </form>
                                    </div>
                                @else
                                    {{-- Assigned to someone else - show current assignment and option to take over --}}
                                    <div>
                                        <label class="block mb-1 font-medium text-neutral-700 dark:text-neutral-300 text-xs">
                                            Case toewijzing
                                        </label>
                                        <div class="flex gap-2">
                                            <div class="flex-1 bg-neutral-50 dark:bg-neutral-700 px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg dark:text-neutral-300 text-sm">
                                                Toegewezen aan: {{ $wooRequest->caseManager->name }}
                                            </div>
                                            <form action="{{ route('woo-requests.pickup', $wooRequest) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-rijksblauw hover:bg-rijksblauw/90 px-4 py-2 rounded-lg font-medium text-white text-sm transition-colors">
                                                    Overnemen
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                {{-- Status Dropdown --}}
                                <div>
                                    <label for="status-select" class="block mb-1 font-medium text-neutral-700 dark:text-neutral-300 text-xs">
                                        Status wijzigen
                                    </label>
                                    <form action="{{ route('woo-requests.update-status', $wooRequest) }}" method="POST" id="status-form">
                                        @csrf
                                        <select name="status"
                                                id="status-select"
                                                onchange="this.form.submit()"
                                                class="block dark:bg-neutral-900 shadow-sm px-3 py-2 border border-neutral-300 focus:border-blue-500 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-blue-500 w-full dark:text-white text-sm">
                                            @foreach(config('woo.woo_request_statuses') as $key => $label)
                                                <option value="{{ $key }}" {{ $wooRequest->status === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>

                                <a href="{{ route('woo-requests.generate-report', $wooRequest) }}" class="inline-block hover:bg-neutral-50 dark:hover:bg-neutral-700 px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg w-full font-medium text-neutral-700 dark:text-neutral-300 text-sm text-center">
                                    Genereer rapport
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth

                {{-- Timeline --}}
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <h3 class="mb-4 font-semibold text-neutral-900 dark:text-white text-sm">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex justify-center items-center bg-blue-100 dark:bg-blue-900/20 rounded-full w-8 h-8">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="bg-neutral-200 dark:bg-neutral-700 w-px h-full"></div>
                            </div>
                            <div class="pb-4">
                                <p class="font-medium text-neutral-900 dark:text-white text-xs">Ingediend</p>
                                <p class="text-neutral-600 dark:text-neutral-400 text-xs">
                                    {{ $wooRequest->submitted_at?->format('d-m-Y H:i') ?? $wooRequest->created_at->format('d-m-Y H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($wooRequest->caseManager)
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <div class="flex justify-center items-center bg-blue-100 dark:bg-blue-900/20 rounded-full w-8 h-8">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @if($wooRequest->status !== 'completed')
                                <div class="bg-neutral-200 dark:bg-neutral-700 w-px h-full"></div>
                                @endif
                            </div>
                            <div class="{{ $wooRequest->status !== 'completed' ? 'pb-4' : '' }}">
                                <p class="font-medium text-neutral-900 dark:text-white text-xs">Toegewezen</p>
                                <p class="text-neutral-600 dark:text-neutral-400 text-xs">
                                    Aan {{ $wooRequest->caseManager->name }}
                                </p>
                            </div>
                        </div>
                        @endif

                        @if($wooRequest->status === 'completed')
                        <div class="flex gap-3">
                            <div class="flex justify-center items-center bg-green-100 dark:bg-green-900/20 rounded-full w-8 h-8">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-neutral-900 dark:text-white text-xs">Afgerond</p>
                                <p class="text-neutral-600 dark:text-neutral-400 text-xs">
                                    {{ $wooRequest->completed_at?->format('d-m-Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
                    <h3 class="mb-4 font-semibold text-neutral-900 dark:text-white text-sm">Statistieken</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center">
                            <dt class="text-neutral-600 dark:text-neutral-400 text-sm">Vragen</dt>
                            <dd>
                                <span class="inline-flex items-center bg-blue-100 dark:bg-blue-900/20 px-2.5 py-0.5 rounded-full font-semibold text-blue-800 dark:text-blue-400 text-xs">
                                    {{ $wooRequest->questions->count() }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-neutral-600 dark:text-neutral-400 text-sm">Documenten</dt>
                            <dd>
                                <span class="inline-flex items-center bg-indigo-100 dark:bg-indigo-900/20 px-2.5 py-0.5 rounded-full font-semibold text-indigo-800 dark:text-indigo-400 text-xs">
                                    {{ $wooRequest->documents->count() }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-neutral-600 dark:text-neutral-400 text-sm">Uploads</dt>
                            <dd>
                                <span class="inline-flex items-center bg-yellow-100 dark:bg-yellow-900/20 px-2.5 py-0.5 rounded-full font-semibold text-yellow-800 dark:text-yellow-400 text-xs">
                                    {{ $wooRequest->submissions->count() }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-neutral-600 dark:text-neutral-400 text-sm">Dagen actief</dt>
                            <dd>
                                <span class="inline-flex items-center bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 rounded-full font-semibold text-gray-800 dark:text-gray-300 text-xs">
                                    {{ ceil($wooRequest->created_at->diffInHours(now()) / 24) }}
                                </span>
                            </dd>
                        </div>
                        @auth
                            @if(auth()->user()->isCaseManager())
                                <div class="flex justify-between items-center">
                                    <dt class="text-neutral-600 dark:text-neutral-400 text-sm">Interne verzoeken</dt>
                                    <dd>
                                        <span class="inline-flex items-center bg-pink-100 dark:bg-pink-900/20 px-2.5 py-0.5 rounded-full font-semibold text-pink-800 dark:text-pink-400 text-xs">
                                            {{ $wooRequest->internalRequests->count() }}
                                        </span>
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
        function copyUploadLink(requestId, buttonElement) {
            const input = document.getElementById('upload-link-' + requestId);
            const linkText = input.value;
            const button = buttonElement;
            const copyTextSpan = button.querySelector('.copy-text');
            const originalText = copyTextSpan.textContent;

            // Use modern Clipboard API if available
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(linkText).then(function() {
                    // Success feedback
                    copyTextSpan.textContent = 'Gekopieerd!';
                    button.classList.remove('text-neutral-700', 'border-neutral-300');
                    button.classList.add('text-green-700', 'bg-green-50', 'border-green-300', 'dark:text-green-400', 'dark:bg-green-900/20', 'dark:border-green-600');

                    // Reset after 2 seconds
                    setTimeout(function() {
                        copyTextSpan.textContent = originalText;
                        button.classList.remove('text-green-700', 'bg-green-50', 'border-green-300', 'dark:text-green-400', 'dark:bg-green-900/20', 'dark:border-green-600');
                        button.classList.add('text-neutral-700', 'border-neutral-300');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy: ', err);
                    // Fallback to old method
                    fallbackCopy(input, button, copyTextSpan, originalText);
                });
            } else {
                // Fallback for older browsers
                fallbackCopy(input, button, copyTextSpan, originalText);
            }
        }

        function fallbackCopy(input, button, copyTextSpan, originalText) {
            // Select the text
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            try {
                // Try to copy using document.execCommand
                const successful = document.execCommand('copy');
                if (successful) {
                    // Success feedback
                    copyTextSpan.textContent = 'Gekopieerd!';
                    button.classList.remove('text-neutral-700', 'border-neutral-300');
                    button.classList.add('text-green-700', 'bg-green-50', 'border-green-300', 'dark:text-green-400', 'dark:bg-green-900/20', 'dark:border-green-600');

                    // Reset after 2 seconds
                    setTimeout(function() {
                        copyTextSpan.textContent = originalText;
                        button.classList.remove('text-green-700', 'bg-green-50', 'border-green-300', 'dark:text-green-400', 'dark:bg-green-900/20', 'dark:border-green-600');
                        button.classList.add('text-neutral-700', 'border-neutral-300');
                    }, 2000);
                } else {
                    alert('Kon link niet automatisch kopiëren. Selecteer de link handmatig en druk op Ctrl+C (of Cmd+C op Mac).');
                }
            } catch (err) {
                console.error('Fallback copy failed: ', err);
                alert('Kon link niet kopiëren. Selecteer de link handmatig en druk op Ctrl+C (of Cmd+C op Mac).');
            }
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
