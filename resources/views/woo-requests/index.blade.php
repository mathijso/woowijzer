<x-layouts.app title="Mijn WOO-verzoeken">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Mijn WOO-verzoeken</h1>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        Overzicht van al uw WOO-verzoeken
                    </p>
                </div>
                <a href="{{ route('woo-requests.create') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nieuw verzoek
                </a>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="mb-6 border-b border-neutral-200 dark:border-neutral-700">
            <nav class="flex -mb-px space-x-8">
                <a href="{{ route('woo-requests.index') }}" 
                   class="px-1 py-4 text-sm font-medium border-b-2 {{ !request('status') ? 'text-blue-600 border-blue-600' : 'text-neutral-600 border-transparent hover:text-neutral-900 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-white' }}">
                    Alle verzoeken
                </a>
                <a href="{{ route('woo-requests.index', ['status' => 'submitted']) }}" 
                   class="px-1 py-4 text-sm font-medium border-b-2 {{ request('status') === 'submitted' ? 'text-blue-600 border-blue-600' : 'text-neutral-600 border-transparent hover:text-neutral-900 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-white' }}">
                    In afwachting
                </a>
                <a href="{{ route('woo-requests.index', ['status' => 'in_progress']) }}" 
                   class="px-1 py-4 text-sm font-medium border-b-2 {{ request('status') === 'in_progress' ? 'text-blue-600 border-blue-600' : 'text-neutral-600 border-transparent hover:text-neutral-900 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-white' }}">
                    In behandeling
                </a>
                <a href="{{ route('woo-requests.index', ['status' => 'completed']) }}" 
                   class="px-1 py-4 text-sm font-medium border-b-2 {{ request('status') === 'completed' ? 'text-blue-600 border-blue-600' : 'text-neutral-600 border-transparent hover:text-neutral-900 hover:border-neutral-300 dark:text-neutral-400 dark:hover:text-white' }}">
                    Afgerond
                </a>
            </nav>
        </div>

        {{-- WOO Requests List --}}
        <div class="space-y-4">
            @forelse($wooRequests as $request)
                <a href="{{ route('woo-requests.show', $request) }}" 
                   class="block p-6 transition bg-white rounded-xl shadow-sm hover:shadow-md dark:bg-neutral-800">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                        {{ $request->title }}
                                    </h3>
                                    
                                    @if($request->description)
                                        <p class="mt-1 text-sm text-neutral-600 line-clamp-2 dark:text-neutral-400">
                                            {{ $request->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap gap-4 items-center mt-3 text-sm text-neutral-600 dark:text-neutral-400">
                                        <span class="flex items-center">
                                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $request->submitted_at?->format('d-m-Y') ?? $request->created_at->format('d-m-Y') }}
                                        </span>

                                        <span class="flex items-center">
                                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $request->questions->count() }} {{ Str::plural('vraag', $request->questions->count()) }}
                                        </span>

                                        <span class="flex items-center">
                                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ $request->documents->count() }} {{ Str::plural('document', $request->documents->count()) }}
                                        </span>
                                    </div>

                                    {{-- Progress Bar --}}
                                    @if($request->questions->count() > 0)
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-xs text-neutral-600 dark:text-neutral-400">
                                                <span>Voortgang</span>
                                                <span>{{ round($request->progress_percentage) }}%</span>
                                            </div>
                                            <div class="overflow-hidden mt-1 w-full bg-neutral-200 rounded-full h-1.5 dark:bg-neutral-700">
                                                <div class="h-full bg-blue-600 rounded-full transition-all" 
                                                     style="width: {{ $request->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="ml-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $request->getStatusBadgeClass() }}">
                                {{ $request->getStatusLabel() }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center bg-white rounded-xl shadow-sm dark:bg-neutral-800">
                    <svg class="mx-auto w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 text-sm font-medium text-neutral-900 dark:text-white">Geen WOO-verzoeken</h3>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                        U heeft nog geen WOO-verzoeken ingediend.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('woo-requests.create') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Nieuw verzoek indienen
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($wooRequests->hasPages())
            <div class="mt-6">
                {{ $wooRequests->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
