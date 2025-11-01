@php
    use App\Models\WooRequest;

    $stats = [
        'total' => WooRequest::count(),
        'unassigned' => WooRequest::whereNull('case_manager_id')->count(),
        'my_cases' => WooRequest::where('case_manager_id', Auth::id())->count(),
        'in_progress' => WooRequest::where('status', 'in_progress')->count(),
        'submitted' => WooRequest::where('status', 'submitted')->count(),
        'completed' => WooRequest::where('status', 'completed')->count(),
    ];

    $recentCases = WooRequest::with(['user', 'questions'])
        ->where('case_manager_id', Auth::id())
        ->latest()
        ->take(5)
        ->get();

    $unassignedCases = WooRequest::with('user')
        ->whereNull('case_manager_id')
        ->latest()
        ->take(3)
        ->get();
@endphp

{{-- Quick Actions --}}
<div class="grid gap-4 md:grid-cols-2">
    <a href="{{ route('cases.index') }}"
       class="rounded-xl p-6 shadow-sm transition bg-rijksblauw hover:bg-rijksdonkerblauw focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-white/10">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="ml-4 text-white">
                <p class="text-sm font-medium">Case Management</p>
                <p class="text-2xl font-bold">{{ $stats['my_cases'] }} actieve cases</p>
            </div>
        </div>
    </a>

    <a href="{{ route('cases.index', ['unassigned' => true]) }}"
       class="rounded-xl p-6 shadow-sm transition bg-rijksblauw hover:bg-rijksdonkerblauw focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-white/10">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-4 text-white">
                <p class="text-sm font-medium">Niet toegewezen</p>
                <p class="text-2xl font-bold">{{ $stats['unassigned'] }} verzoeken</p>
            </div>
        </div>
    </a>
</div>

{{-- Statistics Grid --}}
<div class="grid gap-4 md:grid-cols-4">
    <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-rijksgrijs-1">
                <svg class="h-6 w-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Totaal</p>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-rijksgrijs-1">
                <svg class="h-6 w-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Ingediend</p>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stats['submitted'] }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-rijksgrijs-1">
                <svg class="h-6 w-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">In behandeling</p>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stats['in_progress'] }}</p>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-rijksgrijs-1">
                <svg class="h-6 w-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Afgerond</p>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stats['completed'] }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Unassigned Cases Alert --}}
@if($unassignedCases->count() > 0)
<div class="rounded-xl border border-orange-200 bg-orange-50 p-6 dark:border-orange-900/50 dark:bg-orange-900/10">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-semibold text-orange-900 dark:text-orange-200">
                Niet toegewezen verzoeken
            </h3>
            <div class="mt-2 space-y-2 text-sm text-orange-800 dark:text-orange-300">
                @foreach($unassignedCases as $case)
                    <div class="flex items-center justify-between">
                        <span>{{ Str::limit($case->title, 50) }} - {{ $case->user->name }}</span>
                        <a href="{{ route('cases.show', $case) }}"
                           class="font-medium text-orange-900 hover:text-orange-700 dark:text-orange-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded">
                            Bekijk →
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('cases.index', ['unassigned' => true]) }}"
                   class="text-sm font-semibold text-orange-900 hover:text-orange-700 dark:text-orange-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded">
                    Bekijk alle niet toegewezen verzoeken →
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- My Recent Cases --}}
<div class="rounded-xl bg-white shadow-sm dark:bg-neutral-800">
    <div class="border-b border-neutral-200 p-6 dark:border-neutral-700">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Mijn recente cases</h2>
            <a href="{{ route('cases.index', ['my_cases' => true]) }}"
               class="text-sm text-rijksblauw hover:text-rijksdonkerblauw focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
                Alle cases bekijken →
            </a>
        </div>
    </div>

    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
        @forelse($recentCases as $case)
            <a href="{{ route('cases.show', $case) }}"
               class="block p-6 transition hover:bg-neutral-50 dark:hover:bg-neutral-700/50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h3 class="font-semibold text-neutral-900 dark:text-white">{{ $case->title }}</h3>
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
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $statusColors[$case->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$case->status] ?? $case->status }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                            Ingediend door {{ $case->user->name }} op {{ $case->submitted_at?->format('d-m-Y') ?? $case->created_at->format('d-m-Y') }}
                        </p>
                        <div class="mt-3 flex items-center gap-4 text-sm">
                            <span class="flex items-center text-neutral-600 dark:text-neutral-400">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $case->questions->count() }} vragen
                            </span>
                            @if($case->questions->count() > 0)
                                <div class="flex items-center">
                                    <div class="h-2 w-32 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                                        <div class="h-full rounded-full bg-rijksblauw"
                                             style="width: {{ $case->progress_percentage }}%">
                                        </div>
                                    </div>
                                    <span class="ml-2 text-neutral-600 dark:text-neutral-400">
                                        {{ round($case->progress_percentage) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <svg class="h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-neutral-900 dark:text-white">Geen toegewezen cases</h3>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    U heeft nog geen cases toegewezen gekregen.
                </p>
            </div>
        @endforelse
    </div>
</div>

