<x-layouts.app :title="'Case: ' . $wooRequest->title">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <a href="{{ route('cases.index') }}"
                   class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Terug naar cases
                </a>
            </div>

            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $wooRequest->title }}</h1>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        Ingediend door {{ $wooRequest->user->name }} op {{ $wooRequest->submitted_at?->format('d F Y') ?? $wooRequest->created_at->format('d F Y') }}
                    </p>
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

            {{-- Progress Bar --}}
            <div class="mt-4 rounded-xl bg-white p-4 shadow-sm dark:bg-neutral-800">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-neutral-900 dark:text-white">Voortgang</span>
                    <span class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $progressPercentage }}%</span>
                </div>
                <div class="h-3 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                    <div class="h-full rounded-full bg-blue-600 transition-all" style="width: {{ $progressPercentage }}%"></div>
                </div>
                <div class="mt-2 grid grid-cols-3 gap-2 text-center text-xs">
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
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Questions --}}
                <div class="rounded-xl bg-white shadow-sm dark:bg-neutral-800">
                    <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                Vragen ({{ $wooRequest->questions->count() }})
                            </h2>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                Genereer samenvattingen
                            </button>
                        </div>
                    </div>

                    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @foreach($wooRequest->questions as $question)
                            <div class="p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                                        {{ $question->order }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $question->question_text }}</p>
                                        <div class="mt-2 flex items-center gap-2">
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
                                            <div class="mt-3 rounded-lg bg-blue-50 p-3 text-xs text-neutral-700 dark:bg-blue-900/10 dark:text-neutral-300">
                                                <strong class="block font-semibold text-blue-900 dark:text-blue-200">AI Samenvatting:</strong>
                                                <div class="mt-1 whitespace-pre-wrap">{{ $question->ai_summary }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Documents --}}
                <div class="rounded-xl bg-white shadow-sm dark:bg-neutral-800">
                    <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                Documenten ({{ $wooRequest->documents->count() }})
                            </h2>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                Auto-link documenten
                            </button>
                        </div>
                    </div>
                    <div class="divide-y divide-neutral-200 p-6 dark:divide-neutral-700">
                        @forelse($wooRequest->documents as $document)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/20">
                                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $document->file_name }}</p>
                                        <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                            {{ $document->getFileSizeFormatted() }} •
                                            Geüpload {{ $document->created_at->format('d-m-Y') }}
                                            @if($document->isProcessed())
                                                • <span class="text-green-600 dark:text-green-400">Verwerkt</span>
                                            @else
                                                • <span class="text-yellow-600 dark:text-yellow-400">Wordt verwerkt...</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('documents.show', $document) }}"
                                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    Bekijk →
                                </a>
                            </div>
                        @empty
                            <p class="py-4 text-center text-sm text-neutral-600 dark:text-neutral-400">
                                Nog geen documenten geüpload
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Internal Requests --}}
                <div class="rounded-xl bg-white shadow-sm dark:bg-neutral-800">
                    <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                Interne Verzoeken ({{ $wooRequest->internalRequests->count() }})
                            </h2>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                Nieuw verzoek
                            </button>
                        </div>
                    </div>
                    <div class="divide-y divide-neutral-200 p-6 dark:divide-neutral-700">
                        @forelse($wooRequest->internalRequests as $internalRequest)
                            <div class="py-3">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                            {{ $internalRequest->colleague_name ?? $internalRequest->colleague_email }}
                                        </p>
                                        <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                            {{ Str::limit($internalRequest->description, 80) }}
                                        </p>
                                        <div class="mt-2 flex items-center gap-2 text-xs">
                                            @php
                                                $irStatusColors = [
                                                    'pending' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                                    'submitted' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                                                    'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                                    'expired' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                                                ];
                                                $irStatusLabels = config('woo.internal_request_statuses');
                                            @endphp
                                            <span class="inline-flex rounded-full px-2 py-1 font-medium {{ $irStatusColors[$internalRequest->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $irStatusLabels[$internalRequest->status] ?? $internalRequest->status }}
                                            </span>
                                            <span class="text-neutral-600 dark:text-neutral-400">
                                                {{ $internalRequest->submissions->count() }} upload(s)
                                            </span>
                                            <span class="text-neutral-600 dark:text-neutral-400">
                                                Verloopt {{ $internalRequest->token_expires_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-center text-sm text-neutral-600 dark:text-neutral-400">
                                Nog geen interne verzoeken verstuurd
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Burger Info --}}
                <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Aanvrager</h3>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
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

                {{-- Actions --}}
                <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Acties</h3>
                    <div class="mt-4 space-y-2">
                        <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            Verzoek documenten
                        </button>
                        <button class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                            Status wijzigen
                        </button>
                        <button class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
                            Genereer rapport
                        </button>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Statistieken</h3>
                    <dl class="mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Vragen</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->questions->count() }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Documenten</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->documents->count() }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Uploads</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->submissions->count() }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Dagen actief</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->created_at->diffInDays(now()) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

