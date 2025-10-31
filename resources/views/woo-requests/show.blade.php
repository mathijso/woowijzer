<x-layouts.app :title="$wooRequest->title">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <a href="{{ route('woo-requests.index') }}"
                   class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Terug naar overzicht
                </a>
            </div>

            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $wooRequest->title }}</h1>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        Ingediend op {{ $wooRequest->submitted_at?->format('d F Y') ?? $wooRequest->created_at->format('d F Y') }}
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
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Description --}}
                @if($wooRequest->description)
                <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Beschrijving</h2>
                    <p class="mt-3 whitespace-pre-wrap text-sm text-neutral-600 dark:text-neutral-400">
                        {{ $wooRequest->description }}
                    </p>
                </div>
                @endif

                {{-- Questions --}}
                <div class="rounded-xl bg-white shadow-sm dark:bg-neutral-800">
                    <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                Vragen ({{ $wooRequest->questions->count() }})
                            </h2>
                            @if($wooRequest->questions->count() > 0)
                                <span class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                                    {{ round($wooRequest->progress_percentage) }}% beantwoord
                                </span>
                            @endif
                        </div>
                        @if($wooRequest->questions->count() > 0)
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                                <div class="h-full rounded-full bg-blue-600 transition-all"
                                     style="width: {{ $wooRequest->progress_percentage }}%">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($wooRequest->questions as $question)
                            <div class="p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                                        {{ $question->order }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-neutral-900 dark:text-white">{{ $question->question_text }}</p>
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
                                            <div class="mt-3 rounded-lg bg-neutral-50 p-3 text-xs text-neutral-600 dark:bg-neutral-900/50 dark:text-neutral-400">
                                                <strong class="block text-neutral-900 dark:text-white">Samenvatting:</strong>
                                                <p class="mt-1">{{ Str::limit($question->ai_summary, 200) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    Er zijn nog geen vragen geëxtraheerd uit dit verzoek.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Documents --}}
                @if($wooRequest->documents->count() > 0)
                <div class="rounded-xl bg-white shadow-sm dark:bg-neutral-800">
                    <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Documenten ({{ $wooRequest->documents->count() }})
                        </h2>
                    </div>
                    <div class="divide-y divide-neutral-200 p-6 dark:divide-neutral-700">
                        @foreach($wooRequest->documents as $document)
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
                                            {{ $document->getFileSizeFormatted() }} • {{ $document->created_at->format('d-m-Y') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('documents.show', $document) }}"
                                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                    Bekijk →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Case Manager Info --}}
                @if($wooRequest->caseManager)
                <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Case Manager</h3>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                            {{ $wooRequest->caseManager->initials() }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->caseManager->name }}
                            </p>
                            <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                {{ $wooRequest->caseManager->email }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

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
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Interne verzoeken</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->internalRequests->count() }}
                            </dd>
                        </div>
                        @if($wooRequest->completed_at)
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-neutral-600 dark:text-neutral-400">Afgerond op</dt>
                            <dd class="text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $wooRequest->completed_at->format('d-m-Y') }}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

