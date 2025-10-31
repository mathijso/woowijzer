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
<div class="gap-4 grid md:grid-cols-2">
    <a href="{{ route('cases.index') }}"
       class="bg-rijksblauw hover:bg-rijksdonkerblauw shadow-sm p-6 rounded-xl transition">
        <div class="flex items-center">
            <div class="bg-white/10 p-3 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="ml-4 text-white">
                <p class="font-medium text-sm">Case Management</p>
                <p class="font-bold text-2xl">{{ $stats['my_cases'] }} actieve cases</p>
            </div>
        </div>
    </a>

    <a href="{{ route('cases.index', ['unassigned' => true]) }}"
       class="bg-orange-100 hover:bg-rijksdonkerblauw shadow-sm p-6 rounded-xl transition">
        <div class="flex items-center">
            <div class="bg-orange-100/10 p-3 rounded-lg">
                <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-4 text-orange-700">
                <p class="font-medium text-sm">Niet toegewezen</p>
                <p class="font-bold text-2xl">{{ $stats['unassigned'] }} verzoeken</p>
            </div>
        </div>
    </a>
</div>

{{-- Statistics Grid --}}
<div class="gap-4 grid md:grid-cols-4">
    <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
        <div class="flex items-center">
            <div class="bg-rijksgrijs-1 p-3 rounded-lg">
                <svg class="w-6 h-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium text-neutral-600 dark:text-neutral-400 text-sm">Totaal</p>
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
        <div class="flex items-center">
            <div class="bg-rijksgrijs-1 p-3 rounded-lg">
                <svg class="w-6 h-6 text-rijksblauw" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-medium text-neutral-600 dark:text-neutral-400 text-sm">Ingediend</p>
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">{{ $stats['submitted'] }}</p>
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
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">{{ $stats['in_progress'] }}</p>
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
                <p class="font-bold text-neutral-900 dark:text-white text-2xl">{{ $stats['completed'] }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Unassigned Cases Alert --}}
@if($unassignedCases->count() > 0)
<div class="bg-orange-50 dark:bg-orange-900/10 p-6 border border-orange-200 dark:border-orange-900/50 rounded-xl">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="flex-1 ml-3">
            <h3 class="font-semibold text-orange-900 dark:text-orange-200 text-sm">
                Niet toegewezen verzoeken
            </h3>
            <div class="space-y-2 mt-2 text-orange-800 dark:text-orange-300 text-sm">
                @foreach($unassignedCases as $case)
                    <div class="flex justify-between items-center">
                        <span>{{ Str::limit($case->title, 50) }} - {{ $case->user->name }}</span>
                        <a href="{{ route('cases.show', $case) }}"
                           class="font-medium text-orange-900 hover:text-orange-700 dark:text-orange-200">
                            Bekijk →
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('cases.index', ['unassigned' => true]) }}"
                   class="font-semibold text-orange-900 hover:text-orange-700 dark:text-orange-200 text-sm">
                    Bekijk alle niet toegewezen verzoeken →
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- My Recent Cases --}}
<div class="bg-white dark:bg-neutral-800 shadow-sm rounded-xl">
    <div class="p-6 border-neutral-200 dark:border-neutral-700 border-b">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Mijn recente cases</h2>
            <a href="{{ route('cases.index', ['my_cases' => true]) }}"
               class="text-rijksblauw hover:text-rijksdonkerblauw text-sm">
                Alle cases bekijken →
            </a>
        </div>
    </div>

    <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
        @forelse($recentCases as $case)
            <a href="{{ route('cases.show', $case) }}"
               class="block hover:bg-neutral-50 dark:hover:bg-neutral-700/50 p-6 transition">
                <div class="flex justify-between items-start">
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
                        <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                            Ingediend door {{ $case->user->name }} op {{ $case->submitted_at?->format('d-m-Y') ?? $case->created_at->format('d-m-Y') }}
                        </p>
                        <div class="flex items-center gap-4 mt-3 text-sm">
                            <span class="flex items-center text-neutral-600 dark:text-neutral-400">
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $case->questions->count() }} vragen
                            </span>
                            @if($case->questions->count() > 0)
                                <div class="flex items-center">
                                    <div class="bg-neutral-200 dark:bg-neutral-700 rounded-full w-32 h-2 overflow-hidden">
                                        <div class="bg-rijksblauw rounded-full h-full"
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
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-8 text-center">
                <svg class="mx-auto w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-2 font-semibold text-neutral-900 dark:text-white text-sm">Geen toegewezen cases</h3>
                <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                    U heeft nog geen cases toegewezen gekregen.
                </p>
            </div>
        @endforelse
    </div>
</div>

