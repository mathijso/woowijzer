@php
    $wooRequests = Auth::user()->wooRequests()->with('questions')->latest()->get();
@endphp

{{-- Quick Actions --}}
<div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
    <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Nieuw verzoek</h2>
    <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">Dien een nieuw WOO-verzoek in</p>
    <div class="flex sm:flex-row flex-col gap-3 mt-4">
        <a href="{{ route('woo-requests.create') }}"
           class="inline-flex justify-center items-center bg-rijksblauw hover:bg-rijksdonkerblauw px-4 py-2 rounded-lg font-semibold text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            Met document
        </a>
        <a href="{{ route('woo-requests.create-manual') }}"
           class="inline-flex justify-center items-center bg-blue-50 hover:bg-blue-100 hover:bg-rijksblauw dark:hover:bg-blue-400 px-4 py-2 dark:border-blue-400 rounded-lg font-semibold text-rijksblauw hover:text-white dark:hover:text-white dark:text-blue-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Handmatig aanmaken
        </a>
    </div>
</div>

{{-- Statistics --}}
<div class="gap-4 grid md:grid-cols-3">
    <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
        <div class="flex items-center">
            <div class="bg-rijksgrijs-1 p-3 rounded-lg">
                <svg class="w-6 h-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium text-neutral-600 dark:text-neutral-400 text-sm">Totaal verzoeken</p>
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">{{ $wooRequests->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
        <div class="flex items-center">
            <div class="bg-rijksgrijs-1 p-3 rounded-lg">
                <svg class="w-6 h-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium text-neutral-600 dark:text-neutral-400 text-sm">In behandeling</p>
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">
                    {{ $wooRequests->whereIn('status', ['in_review', 'in_progress'])->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
        <div class="flex items-center">
            <div class="bg-rijksgrijs-1 p-3 rounded-lg">
                <svg class="w-6 h-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium text-neutral-600 dark:text-neutral-400 text-sm">Afgerond</p>
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">
                    {{ $wooRequests->where('status', 'completed')->count() }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Recent WOO Requests --}}
<div class="bg-white dark:bg-neutral-800 shadow-sm rounded-xl">
    <div class="p-6 border-neutral-200 dark:border-neutral-700 border-b">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Mijn WOO-verzoeken</h2>
            <a href="{{ route('woo-requests.index') }}" class="text-rijksblauw hover:text-rijksdonkerblauw text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
                Alles bekijken →
            </a>
        </div>
    </div>

    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
        @forelse($wooRequests->take(5) as $request)
            <a href="{{ route('woo-requests.show', $request) }}"
               class="block hover:bg-neutral-50 dark:hover:bg-neutral-700/50 p-6 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-neutral-900 dark:text-white">{{ $request->title }}</h3>
                        <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                            Ingediend op {{ $request->submitted_at?->format('d-m-Y') ?? $request->created_at->format('d-m-Y') }}
                        </p>
                        <div class="flex items-center gap-4 mt-2 text-neutral-600 dark:text-neutral-400 text-sm">
                            <span class="flex items-center">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $request->questions->count() }} vragen
                            </span>
                            @if($request->questions->count() > 0)
                                <span>{{ round($request->progress_percentage) }}% beantwoord</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
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
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$request->status] ?? $request->status }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-8 text-center">
                <svg class="mx-auto w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 font-semibold text-neutral-900 dark:text-white text-sm">Geen verzoeken</h3>
                <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                    U heeft nog geen WOO-verzoeken ingediend.
                </p>
                <div class="mt-4">
                    <a href="{{ route('woo-requests.create') }}"
                       class="inline-flex items-center bg-rijksblauw hover:bg-rijksdonkerblauw px-4 py-2 rounded-lg font-semibold text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Nieuw verzoek indienen
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

