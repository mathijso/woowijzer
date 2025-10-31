<x-layouts.app :title="$document->file_name">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <a href="{{ route('documents.index', ['woo_request_id' => $document->woo_request_id]) }}" 
                   class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Terug naar documenten
                </a>
            </div>
            
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $document->file_name }}</h1>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        Geüpload op {{ $document->created_at->format('d F Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('documents.download', $document) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
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
            <div class="lg:col-span-2 space-y-6">
                {{-- Processing Status --}}
                @if(!$document->isProcessed())
                    <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/10">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="ml-3 text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                Document wordt verwerkt...
                            </p>
                        </div>
                    </div>
                @endif

                {{-- AI Summary --}}
                @if($document->ai_summary)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">AI Samenvatting</h2>
                    <div class="mt-3 prose prose-sm dark:prose-invert max-w-none">
                        <p class="text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">{{ $document->ai_summary }}</p>
                    </div>
                </div>
                @endif

                {{-- Content --}}
                @if($document->content_markdown)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Document Inhoud</h2>
                    <div class="mt-3 prose prose-sm dark:prose-invert max-w-none">
                        <div class="p-4 overflow-auto rounded-lg bg-neutral-50 dark:bg-neutral-900 max-h-96">
                            <pre class="text-xs text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">{{ Str::limit($document->content_markdown, 2000) }}</pre>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Linked Questions --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Gekoppelde Vragen ({{ $document->questions->count() }})
                        </h2>
                    </div>

                    @forelse($document->questions as $question)
                        <div class="py-3 border-t border-neutral-200 dark:border-neutral-700">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm text-neutral-900 dark:text-white">{{ $question->question_text }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        @if($question->pivot->relevance_score)
                                            <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                                                {{ round($question->pivot->relevance_score * 100) }}% relevant
                                            </span>
                                        @endif
                                        @if($question->pivot->confirmed_by_case_manager)
                                            <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                                Bevestigd
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">
                                                Niet bevestigd
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-sm text-center text-neutral-600 dark:text-neutral-400">
                            Dit document is nog niet gekoppeld aan vragen
                        </p>
                    @endforelse
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
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white break-all">{{ $document->file_name }}</dd>
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
                        @if($document->processed_at)
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Verwerkt</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $document->processed_at->format('d-m-Y H:i') }}</dd>
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
                    <a href="{{ route('woo-requests.show', $document->wooRequest) }}" 
                       class="block mt-3 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        {{ $document->wooRequest->title }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

