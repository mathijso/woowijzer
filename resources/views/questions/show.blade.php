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

                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $question->question_text }}</h1>
                            <div class="flex gap-2 items-center mt-2">
                                @livewire('question-status-badge', ['question' => $question])
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

                    @livewire('question-documents-list', ['question' => $question, 'wooRequest' => $wooRequest])
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">



                {{-- Answer Question Form --}}
                @auth
                    @if(auth()->user()->isCaseManager())
                        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Vraag Beantwoorden</h3>
                            <form action="{{ route('cases.questions.update', [$wooRequest, $question]) }}" method="POST" class="mt-4 space-y-4">
                                @csrf
                                @method('PUT')

                                @livewire('question-status-buttons', ['question' => $question, 'wooRequest' => $wooRequest])




                            </form>

                            @if($question->documents->where('pivot.confirmed_by_case_manager', true)->count() > 0)
                            <form action="{{ route('cases.questions.generate-summary', [$wooRequest, $question]) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit"
                                        class="inline-flex justify-center items-center px-4 py-2 w-full text-sm font-medium rounded-lg border text-neutral-700 border-neutral-300 bg-neutral-50 hover:bg-neutral-100 dark:border-neutral-600 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
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
                    <a href="{{ route('woo-requests.show', [$wooRequest, 'questions']) }}"
                       class="block mt-3 text-sm text-rijksblauw hover:text-blue-700 dark:text-blue-400">
                        {{ $wooRequest->title }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

