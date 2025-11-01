<div>
    @if($viewType === 'detailed')
        {{-- Detailed View: Case Managers Header --}}
        <div class="p-4 mt-4 bg-white rounded-xl shadow-sm dark:bg-neutral-800 transition-all duration-300">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-neutral-900 dark:text-white">Voortgang</span>
                <span class="text-sm font-semibold text-neutral-900 dark:text-white transition-all duration-500">
                    {{ number_format($progressPercentage, 0) }}%
                </span>
            </div>
            <div class="overflow-hidden h-3 rounded-full bg-neutral-200 dark:bg-neutral-700">
                <div class="h-full rounded-full transition-all duration-700 ease-out bg-rijksblauw"
                     style="width: {{ $progressPercentage }}%"></div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-2 text-xs text-center">
                <button
                    wire:click="filterByStatus('unanswered')"
                    class="p-1 rounded transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:scale-105 cursor-pointer group"
                    title="Klik om onbeantwoorde vragen te filteren">
                    <span class="font-semibold text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200 transition-colors">
                        {{ $questionStats['unanswered'] }}
                    </span>
                    <span class="text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200 transition-colors">
                        onbeantwoord
                    </span>
                </button>
                <button
                    wire:click="filterByStatus('partially_answered')"
                    class="p-1 rounded transition-all duration-200 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 hover:scale-105 cursor-pointer group"
                    title="Klik om gedeeltelijk beantwoorde vragen te filteren">
                    <span class="font-semibold text-yellow-600 dark:text-yellow-400 group-hover:text-yellow-700 dark:group-hover:text-yellow-300 transition-colors">
                        {{ $questionStats['partially_answered'] }}
                    </span>
                    <span class="text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200 transition-colors">
                        gedeeltelijk
                    </span>
                </button>
                <button
                    wire:click="filterByStatus('answered')"
                    class="p-1 rounded transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20 hover:scale-105 cursor-pointer group"
                    title="Klik om beantwoorde vragen te filteren">
                    <span class="font-semibold text-green-600 dark:text-green-400 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors">
                        {{ $questionStats['answered'] }}
                    </span>
                    <span class="text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200 transition-colors">
                        beantwoord
                    </span>
                </button>
            </div>
        </div>
    @else
        {{-- Simple View: Guests & Authenticated Users --}}
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-neutral-800 transition-all duration-300">
            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Voortgang</h2>
            <div class="mt-4">
                <div class="flex justify-between items-center text-sm text-neutral-600 dark:text-neutral-400">
                    <span>Beantwoorde vragen</span>
                    <span class="font-semibold transition-all duration-500">
                        {{ number_format($progressPercentage, 0) }}%
                    </span>
                </div>
                <div class="overflow-hidden mt-2 w-full h-2.5 rounded-full bg-neutral-200 dark:bg-neutral-700">
                    <div class="h-full rounded-full transition-all duration-700 ease-out bg-rijksblauw"
                         style="width: {{ $progressPercentage }}%"></div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <button
                        wire:click="filterByStatus('all')"
                        class="p-3 text-center rounded-lg bg-neutral-50 dark:bg-neutral-900 transition-all duration-200 hover:bg-neutral-100 dark:hover:bg-neutral-800 hover:scale-105 cursor-pointer group"
                        title="Klik om alle vragen te tonen">
                        <div class="text-2xl font-bold text-neutral-900 dark:text-white group-hover:text-rijksblauw dark:group-hover:text-rijkscyaan transition-colors duration-200">
                            {{ $questionStats['total'] }}
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200 transition-colors">
                            Totaal vragen
                        </div>
                    </button>
                    <button
                        wire:click="filterByStatus('answered')"
                        class="p-3 text-center bg-green-50 rounded-lg dark:bg-green-900/20 transition-all duration-200 hover:bg-green-100 dark:hover:bg-green-900/30 hover:scale-105 cursor-pointer group"
                        title="Klik om beantwoorde vragen te tonen">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors duration-200">
                            {{ $questionStats['answered'] }}
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200 transition-colors">
                            Beantwoord
                        </div>
                    </button>
                    <button
                        wire:click="filterByStatus('unanswered')"
                        class="p-3 text-center bg-red-50 rounded-lg dark:bg-red-900/20 transition-all duration-200 hover:bg-red-100 dark:hover:bg-red-900/30 hover:scale-105 cursor-pointer group"
                        title="Klik om openstaande vragen te tonen">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400 group-hover:text-red-700 dark:group-hover:text-red-300 transition-colors duration-200">
                            {{ $questionStats['unanswered'] }}
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400 group-hover:text-neutral-900 dark:group-hover:text-neutral-200 transition-colors">
                            Open
                        </div>
                    </button>
                </div>
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

