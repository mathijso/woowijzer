<x-layouts.app title="Mijn WOO-verzoeken">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Mijn WOO-verzoeken</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Overzicht van al uw ingediende WOO-verzoeken
                </p>
            </div>
            <a href="{{ route('woo-requests.create') }}"
               class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nieuw verzoek
            </a>
        </div>

        <div class="grid gap-4">
            @forelse($wooRequests as $request)
                <a href="{{ route('woo-requests.show', $request) }}"
                   class="block rounded-xl bg-white p-6 shadow-sm transition hover:shadow-md dark:bg-neutral-800">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                        {{ $request->title }}
                                    </h3>
                                    @if($request->description)
                                        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ Str::limit($request->description, 150) }}
                                        </p>
                                    @endif
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
                                <span class="ml-4 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$request->status] ?? $request->status }}
                                </span>
                            </div>

                            <div class="mt-4 flex items-center gap-6 text-sm text-neutral-600 dark:text-neutral-400">
                                <span class="flex items-center">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $request->submitted_at?->format('d-m-Y') ?? $request->created_at->format('d-m-Y') }}
                                </span>

                                <span class="flex items-center">
                                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $request->questions->count() }} vragen
                                </span>

                                @if($request->caseManager)
                                    <span class="flex items-center">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $request->caseManager->name }}
                                    </span>
                                @endif
                            </div>

                            @if($request->questions->count() > 0)
                                <div class="mt-3 flex items-center gap-2">
                                    <div class="h-2 flex-1 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                                        <div class="h-full rounded-full bg-blue-600 transition-all"
                                             style="width: {{ $request->progress_percentage }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                                        {{ round($request->progress_percentage) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-xl bg-white p-12 text-center shadow-sm dark:bg-neutral-800">
                    <svg class="mx-auto h-16 w-16 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-neutral-900 dark:text-white">
                        Nog geen WOO-verzoeken
                    </h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                        U heeft nog geen WOO-verzoeken ingediend. Dien uw eerste verzoek in.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('woo-requests.create') }}"
                           class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nieuw WOO-verzoek
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($wooRequests->hasPages())
            <div class="mt-6">
                {{ $wooRequests->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>

