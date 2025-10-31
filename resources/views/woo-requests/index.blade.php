<x-layouts.app title="Mijn WOO-verzoeken">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="font-bold text-neutral-900 dark:text-white text-2xl">Mijn WOO-verzoeken</h1>
                    <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                        Overzicht van al uw WOO-verzoeken
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('woo-requests.create') }}" 
                       class="inline-flex items-center bg-rijksblauw hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold text-white text-sm">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Met document
                    </a>
                    <a href="{{ route('woo-requests.create-manual') }}" 
                       class="inline-flex items-center bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 px-4 py-2 rounded-lg font-semibold text-rijksblauw dark:text-blue-400 text-sm">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Handmatig
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="mb-6 border-neutral-200 dark:border-neutral-700 border-b">
            <nav class="flex space-x-8 -mb-px">
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
                   class="block bg-white dark:bg-neutral-800 shadow-sm hover:shadow-md p-6 rounded-xl transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="flex justify-center items-center bg-blue-100 dark:bg-blue-900/20 rounded-lg w-12 h-12">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-neutral-900 dark:text-white text-lg">
                                        {{ $request->title }}
                                    </h3>
                                    
                                    @if($request->description)
                                        <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm line-clamp-2">
                                            {{ $request->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-4 mt-3 text-neutral-600 dark:text-neutral-400 text-sm">
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
                                            <div class="flex justify-between items-center text-neutral-600 dark:text-neutral-400 text-xs">
                                                <span>Voortgang</span>
                                                <span>{{ round($request->progress_percentage) }}%</span>
                                            </div>
                                            <div class="bg-neutral-200 dark:bg-neutral-700 mt-1 rounded-full w-full h-1.5 overflow-hidden">
                                                <div class="bg-rijksblauw rounded-full h-full transition-all" 
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
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-12 rounded-xl text-center">
                    <svg class="mx-auto w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 font-medium text-neutral-900 dark:text-white text-sm">Geen WOO-verzoeken</h3>
                    <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                        U heeft nog geen WOO-verzoeken ingediend.
                    </p>
                    <div class="flex justify-center gap-2 mt-6">
                        <a href="{{ route('woo-requests.create') }}" 
                           class="inline-flex items-center bg-rijksblauw hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold text-white text-sm">
                            Met document
                        </a>
                        <a href="{{ route('woo-requests.create-manual') }}" 
                           class="inline-flex items-center bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 px-4 py-2 rounded-lg font-semibold text-rijksblauw dark:text-blue-400 text-sm">
                            Handmatig
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
