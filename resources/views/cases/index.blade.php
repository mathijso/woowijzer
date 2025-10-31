<x-layouts.app title="Case Management">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="font-bold text-neutral-900 dark:text-white text-2xl">Case Management</h1>
            <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                Beheer en volg alle WOO-verzoeken
            </p>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-4 bg-white dark:bg-neutral-800 shadow-sm mb-6 p-4 rounded-xl">
            <a href="{{ route('cases.index') }}"
               class="rounded-lg px-4 py-2 text-sm font-medium transition {{ !request()->has('status') && !request()->has('my_cases') && !request()->has('unassigned') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }}">
                Alle cases
            </a>
            <a href="{{ route('cases.index', ['my_cases' => true]) }}"
               class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->has('my_cases') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }}">
                Mijn cases
            </a>
            <a href="{{ route('cases.index', ['unassigned' => true]) }}"
               class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request()->has('unassigned') ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }}">
                Niet toegewezen
                @if($stats['unassigned'] > 0)
                    <span class="inline-flex justify-center items-center bg-orange-600 ml-1 px-2 py-0.5 rounded-full font-bold text-white text-xs">
                        {{ $stats['unassigned'] }}
                    </span>
                @endif
            </a>

            <div class="flex gap-2 ml-auto">
                <a href="{{ route('cases.index', ['status' => 'submitted']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request('status') === 'submitted' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }}">
                    Ingediend
                </a>
                <a href="{{ route('cases.index', ['status' => 'in_progress']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request('status') === 'in_progress' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }}">
                    In behandeling
                </a>
                <a href="{{ route('cases.index', ['status' => 'completed']) }}"
                   class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request('status') === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-700' }}">
                    Afgerond
                </a>
            </div>
        </div>

        {{-- Cases List --}}
        <div class="gap-4 grid">
            @forelse($wooRequests as $request)
                <div class="rounded-xl bg-white p-6 shadow-sm transition hover:shadow-md dark:bg-neutral-800">
                    <div class="flex items-start justify-between">
                        <a href="{{ route('cases.show', $request) }}" class="flex-1">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="font-semibold text-neutral-900 dark:text-white text-lg">
                                            {{ $request->title }}
                                        </h3>
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
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$request->status] ?? $request->status }}
                                        </span>
                                        @if(!$request->case_manager_id)
                                            <span class="inline-flex bg-orange-100 dark:bg-orange-900/20 px-2 py-1 rounded-full font-semibold text-orange-700 dark:text-orange-400 text-xs">
                                                Niet toegewezen
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 mt-2 text-neutral-600 dark:text-neutral-400 text-sm">
                                        <span class="flex items-center">
                                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $request->user->name }}
                                        </span>
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
                                            {{ $request->questions_count ?? 0 }} vragen
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $request->documents_count ?? 0 }} documenten
                                        </span>
                                    </div>
                                    @if($request->questions_count > 0)
                                        <div class="flex items-center gap-2 mt-3">
                                            <div class="flex-1 bg-neutral-200 dark:bg-neutral-700 rounded-full h-2 overflow-hidden">
                                                <div class="bg-rijksblauw rounded-full h-full transition-all"
                                                     style="width: {{ $request->progress_percentage ?? 0 }}%">
                                                </div>
                                            </div>
                                            <span class="font-medium text-neutral-600 dark:text-neutral-400 text-sm">
                                                {{ round($request->progress_percentage ?? 0) }}%
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <div class="ml-4 flex items-center gap-3">
                            @if(!$request->case_manager_id)
                                {{-- Pick up case button --}}
                                <form action="{{ route('cases.pickup', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                                        <svg class="mr-1.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        Oppakken
                                    </button>
                                </form>
                                
                                {{-- Assign to another case manager dropdown --}}
                                <form action="{{ route('cases.assign', $request) }}" method="POST" class="inline" id="assign-form-{{ $request->id }}">
                                    @csrf
                                    <select name="case_manager_id"
                                            onchange="this.form.submit()"
                                            class="text-xs px-2 py-1.5 rounded-lg border border-neutral-300 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                        <option value="">Toewijzen aan...</option>
                                        @foreach($caseManagers as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @elseif($request->case_manager_id === auth()->id())
                                {{-- Reassign button if assigned to current user --}}
                                <form action="{{ route('cases.assign', $request) }}" method="POST" class="inline" id="reassign-form-{{ $request->id }}">
                                    @csrf
                                    <select name="case_manager_id"
                                            onchange="this.form.submit()"
                                            class="text-xs px-2 py-1.5 rounded-lg border border-neutral-300 bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-900 dark:text-white">
                                        <option value="">Herverdelen...</option>
                                        @foreach($caseManagers->where('id', '!=', auth()->id()) as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                        <option value="">Geen case manager</option>
                                    </select>
                                </form>
                            @endif
                            <a href="{{ route('cases.show', $request) }}" class="text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-neutral-800 shadow-sm p-12 rounded-xl text-center">
                    <svg class="mx-auto w-16 h-16 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="mt-4 font-semibold text-neutral-900 dark:text-white text-lg">
                        Geen cases gevonden
                    </h3>
                    <p class="mt-2 text-neutral-600 dark:text-neutral-400 text-sm">
                        Er zijn geen cases die aan uw filters voldoen.
                    </p>
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

