<x-layouts.app :title="'Vraag ' . $question->order">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-4">
                <a href="{{ route('woo-requests.show', $wooRequest) }}"
                   class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Terug naar WOO verzoek
                </a>
            </div>

            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex gap-3 items-start">
                        <div class="flex flex-shrink-0 justify-center items-center w-10 h-10 text-lg font-semibold text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                            {{ $question->order }}
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $question->question_text }}</h1>
                            <div class="flex gap-2 items-center mt-2">
                                @php
                                    $questionStatusColors = [
                                        'unanswered' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                        'partially_answered' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'answered' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                    ];
                                    $questionStatusLabels = config('woo.question_statuses');
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $questionStatusColors[$question->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $questionStatusLabels[$question->status] ?? $question->status }}
                                </span>
                                @if($question->documents->count() > 0)
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">
                                        {{ $question->documents->count() }} document(en) gekoppeld
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- Main Content --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- AI Summary --}}
                @if($question->ai_summary)
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">AI Samenvatting</h2>
                    <div class="mt-3 max-w-none prose prose-sm dark:prose-invert">
                        <p class="whitespace-pre-wrap text-neutral-700 dark:text-neutral-300">{{ $question->ai_summary }}</p>
                    </div>
                </div>
                @endif

                {{-- Linked Documents --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                            Gekoppelde Documenten ({{ $question->documents->count() }})
                        </h2>
                    </div>

                    @forelse($question->documents as $document)
                        <div class="py-4 border-t border-neutral-200 dark:border-neutral-700 first:border-t-0">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <a href="{{ route('cases.documents.show', [$wooRequest, $document]) }}"
                                       class="block">
                                        <p class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $document->file_name }}
                                        </p>
                                    </a>
                                    <div class="flex gap-2 items-center mt-2">
                                        @if($document->pivot->relevance_score)
                                            <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
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
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-lg border border-neutral-300 hover:bg-neutral-50 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700">
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
                            Deze vraag heeft nog geen gekoppelde documenten
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Question Info --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Vraag Informatie</h3>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Volgnummer</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $question->order }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $questionStatusColors[$question->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $questionStatusLabels[$question->status] ?? $question->status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Gekoppelde documenten</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">
                                {{ $question->documents->count() }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Aangemaakt</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $question->created_at->format('d-m-Y H:i') }}</dd>
                        </div>
                        @if($question->updated_at && $question->updated_at->ne($question->created_at))
                        <div>
                            <dt class="text-xs text-neutral-600 dark:text-neutral-400">Laatst bijgewerkt</dt>
                            <dd class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">{{ $question->updated_at->format('d-m-Y H:i') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Answer Question Form --}}
                @auth
                    @if(auth()->user()->isCaseManager())
                        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Vraag Beantwoorden</h3>
                            <form action="{{ route('cases.questions.update', [$wooRequest, $question]) }}" method="POST" class="mt-4 space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="status" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        Status
                                    </label>
                                    <select name="status"
                                            id="status"
                                            class="block px-3 py-2 mt-1 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                        @foreach(config('woo.question_statuses') as $key => $label)
                                            <option value="{{ $key }}" {{ $question->status === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="ai_summary" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                        AI Samenvatting (optioneel)
                                    </label>
                                    <textarea name="ai_summary"
                                              id="ai_summary"
                                              rows="4"
                                              class="block px-3 py-2 mt-1 w-full text-sm rounded-lg border shadow-sm border-neutral-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white"
                                              placeholder="Voeg een samenvatting toe...">{{ old('ai_summary', $question->ai_summary) }}</textarea>
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit"
                                            class="inline-flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Opslaan
                                    </button>
                                </div>
                            </form>

                            @if($question->documents->where('pivot.confirmed_by_case_manager', true)->count() > 0)
                            <form action="{{ route('cases.questions.generate-summary', [$wooRequest, $question]) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit"
                                        class="inline-flex justify-center items-center px-4 py-2 w-full text-sm font-medium text-neutral-700 rounded-lg border border-neutral-300 bg-neutral-50 hover:bg-neutral-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                                    Genereer samenvatting
                                </button>
                            </form>
                            @endif
                        </div>
                    @endif
                @endauth

                {{-- WOO Request Link --}}
                <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">WOO Verzoek</h3>
                    <a href="{{ route('woo-requests.show', $wooRequest) }}"
                       class="block mt-3 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        {{ $wooRequest->title }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

