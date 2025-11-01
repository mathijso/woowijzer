<div>
    @if($viewType === 'detailed')
        {{-- Detailed View: Case Managers Status Timeline --}}
        <div class="p-4 mt-4 bg-white rounded-xl shadow-sm transition-all duration-300 dark:bg-neutral-800">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-medium text-neutral-900 dark:text-white">Status verzoek</span>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $wooRequest->getStatusBadgeClass() }}">
                    {{ $wooRequest->getStatusLabel() }}
                </span>
            </div>

            {{-- Horizontal Status Timeline --}}
            <div class="flex overflow-x-auto relative justify-between items-start pb-4">
                @foreach($statusPhases as $index => $phase)
                    <div class="flex relative flex-col flex-1 items-center min-w-0 group" wire:key="phase-detail-{{ $phase['key'] }}">
                        {{-- Horizontal Line --}}
                        @if(!$loop->last)
                            <div class="absolute left-1/2 top-3.5 w-full h-0.5 transition-all duration-500 z-0
                                {{ $phase['completed'] ? 'bg-rijksblauw dark:bg-rijkscyaan' : 'bg-neutral-200 dark:bg-neutral-700' }}"
                                style="width: calc(100% - 28px); left: calc(50% + 14px);">
                            </div>
                        @endif

                        {{-- Status Icon --}}
                        <div class="relative z-10 flex-shrink-0 mb-2">
                            <div class="flex items-center justify-center w-7 h-7 rounded-full transition-all duration-500
                                {{ $phase['completed']
                                    ? 'bg-green-700 dark:bg-rijkscyaan text-white dark:text-neutral-900 shadow-md'
                                    : ($phase['current']
                                        ? 'bg-blue-100 dark:bg-blue-900/30 text-rijksblauw dark:text-rijkscyaan border-2 border-rijksblauw dark:border-rijkscyaan animate-pulse'
                                        : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-400 dark:text-neutral-500')
                                }}">
                                @if($phase['completed'])
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($phase['current'])
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                @else
                                    <div class="w-2 h-2 bg-current rounded-full"></div>
                                @endif
                            </div>
                        </div>

                        {{-- Status Content --}}
                        <div class="flex-1 px-2 w-full text-center">
                            <div class="flex flex-col items-center mb-1">
                                <h4 class="text-xs font-semibold transition-colors duration-300 mb-1
                                    {{ $phase['completed'] || $phase['current']
                                        ? 'text-neutral-900 dark:text-white'
                                        : 'text-neutral-500 dark:text-neutral-400'
                                    }}">
                                    {{ $phase['title'] }}
                                </h4>
                                @if($phase['date'])
                                    <span class="text-[10px] font-medium text-neutral-500 dark:text-neutral-400">
                                        {{ $phase['date'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] leading-tight transition-colors duration-300 mb-2
                                {{ $phase['completed'] || $phase['current']
                                    ? 'text-neutral-600 dark:text-neutral-300'
                                    : 'text-neutral-400 dark:text-neutral-500'
                                }}">
                                {{ $phase['description'] }}
                            </p>


                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- Simple View: Status Tracking Timeline --}}
        <div class="p-6 bg-white rounded-xl shadow-sm transition-all duration-300 dark:bg-neutral-800">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Status van uw verzoek</h2>
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $wooRequest->getStatusBadgeClass() }}">
                    {{ $wooRequest->getStatusLabel() }}
                </span>
            </div>

            {{-- Status Timeline --}}
            <div class="space-y-4">
                @foreach($statusPhases as $index => $phase)
                    <div class="flex relative gap-4 group" wire:key="phase-{{ $phase['key'] }}">
                        {{-- Vertical Line --}}
                        @if(!$loop->last)
                            <div class="absolute left-5 top-12 bottom-0 w-0.5 transition-all duration-500
                                {{ $phase['completed'] ? 'bg-rijksblauw dark:bg-rijkscyaan' : 'bg-neutral-200 dark:bg-neutral-700' }}">
                            </div>
                        @endif

                        {{-- Status Icon --}}
                        <div class="relative z-10 flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-500
                                {{ $phase['completed']
                                    ? 'bg-green-700 dark:bg-rijkscyaan text-white dark:text-neutral-900 shadow-lg scale-110'
                                    : ($phase['current']
                                        ? 'bg-blue-100 dark:bg-blue-900/30 text-rijksblauw dark:text-rijkscyaan border-2 border-rijksblauw dark:border-rijkscyaan animate-pulse'
                                        : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-400 dark:text-neutral-500')
                                }}">
                                @if($phase['completed'])
                                    {{-- Checkmark for completed --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @elseif($phase['current'])
                                    {{-- Spinner for current --}}
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                @else
                                    {{-- Circle for pending --}}
                                    <div class="w-3 h-3 bg-current rounded-full"></div>
                                @endif
                            </div>
                        </div>

                        {{-- Status Content --}}
                        <div class="flex-1 pb-8 -mt-1 transition-all duration-300
                            {{ $phase['current'] ? 'transform scale-[1.02]' : '' }}">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-semibold transition-colors duration-300
                                    {{ $phase['completed'] || $phase['current']
                                        ? 'text-neutral-900 dark:text-white'
                                        : 'text-neutral-500 dark:text-neutral-400'
                                    }}">
                                    {{ $phase['title'] }}
                                </h3>
                                @if($phase['date'])
                                    <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                        {{ $phase['date'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm transition-colors duration-300
                                {{ $phase['completed'] || $phase['current']
                                    ? 'text-neutral-600 dark:text-neutral-300'
                                    : 'text-neutral-400 dark:text-neutral-500'
                                }}">
                                {{ $phase['description'] }}
                            </p>

                            {{-- Extra info for processing phase --}}
                            @if($phase['key'] === 'processing' && $phase['current'])
                                <div class="p-3 mt-3 bg-blue-50 rounded-lg dark:bg-blue-900/20">
                                    <div class="flex justify-between items-center mb-2 text-xs font-medium text-blue-700 dark:text-blue-300">
                                        <span>Vragen beantwoord</span>
                                        <span>{{ $questionStats['answered'] }} van {{ $questionStats['total'] }}</span>
                                    </div>
                                    <div class="overflow-hidden h-1.5 bg-blue-200 rounded-full dark:bg-blue-900/40">
                                        <div class="h-full rounded-full transition-all duration-700 bg-rijksblauw dark:bg-rijkscyaan"
                                             style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-3 gap-3 pt-6 mt-6 border-t border-neutral-200 dark:border-neutral-700">
                <button
                    wire:click="filterByStatus('all')"
                    class="p-3 text-center rounded-lg transition-all duration-200 cursor-pointer bg-neutral-50 dark:bg-neutral-900 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:scale-105 group"
                    title="Bekijk alle vragen">
                    <div class="text-xl font-bold transition-colors duration-200 text-neutral-900 dark:text-white group-hover:text-rijksblauw dark:group-hover:text-rijkscyaan">
                        {{ $questionStats['total'] }}
                    </div>
                    <div class="text-xs transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">
                        Totaal vragen
                    </div>
                </button>
                <button
                    wire:click="filterByStatus('answered')"
                    class="p-3 text-center bg-green-50 rounded-lg transition-all duration-200 cursor-pointer dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 hover:scale-105 group"
                    title="Bekijk beantwoorde vragen">
                    <div class="text-xl font-bold text-green-600 transition-colors duration-200 dark:text-green-400 group-hover:text-green-700 dark:group-hover:text-green-300">
                        {{ $questionStats['answered'] }}
                    </div>
                    <div class="text-xs transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">
                        Beantwoord
                    </div>
                </button>
                <button
                    wire:click="filterByStatus('unanswered')"
                    class="p-3 text-center bg-orange-50 rounded-lg transition-all duration-200 cursor-pointer dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/30 hover:scale-105 group"
                    title="Bekijk openstaande vragen">
                    <div class="text-xl font-bold text-orange-600 transition-colors duration-200 dark:text-orange-400 group-hover:text-orange-700 dark:group-hover:text-orange-300">
                        {{ $questionStats['unanswered'] }}
                    </div>
                    <div class="text-xs transition-colors text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200">
                        Open
                    </div>
                </button>
            </div>
        </div>
    @endif

    {{-- Subtle pulse animation on update --}}
    <style>
        @keyframes subtle-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }

        [wire\:loading] {
            animation: subtle-pulse 1s ease-in-out;
        }
    </style>
</div>

