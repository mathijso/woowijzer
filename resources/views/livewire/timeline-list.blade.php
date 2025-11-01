<div>
    @if(!empty($events))
        <div class="relative pl-2">
            {{-- Timeline vertical line --}}
            <div class="absolute bottom-0 top-1 left-5 w-0.5 bg-neutral-200 dark:bg-neutral-700"></div>

            <div class="space-y-0">
                @foreach($events as $index => $event)
                    @php
                        $isExpanded = $this->isExpanded($index);
                        $hasDetails = isset($event['summary']) || (isset($event['actors']) && !empty($event['actors']));
                    @endphp
                    <div class="flex relative gap-2 mb-3 last:mb-0">
                        {{-- Timeline dot --}}
                        <div class="flex relative z-10 flex-shrink-0 justify-center items-start pt-0.5">
                            <div class="flex justify-center items-center w-6 h-6 bg-white rounded-full border-2 border-blue-500 shadow-sm dark:bg-neutral-800 dark:border-blue-400">
                                <div class="w-2 h-2 bg-blue-500 rounded-full dark:bg-blue-400"></div>
                            </div>
                        </div>

                        {{-- Event content --}}
                        <div class="flex-1 pb-2 min-w-0">
                            <div class="relative">
                                @if($hasDetails)
                                    <button
                                        wire:click="toggleEvent({{ $index }})"
                                        class="flex justify-between items-start w-full p-2.5 text-left transition rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-900/50 {{ $isExpanded ? 'bg-neutral-50 dark:bg-neutral-900' : '' }} cursor-pointer"
                                    >
                                @else
                                    <div class="flex justify-between items-start p-2.5 w-full text-left rounded-md">
                                @endif
                                    <div class="flex-1 pr-3 min-w-0">
                                        <div class="flex flex-wrap gap-2 items-start">
                                            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $event['title'] ?? 'Gebeurtenis' }}</h3>
                                            @if(isset($event['type']))
                                                <span class="flex-shrink-0 px-2 py-0.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/20 dark:text-blue-400">
                                                    {{ $event['type'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap gap-2 items-center mt-1">
                                            <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                                {{ $event['date'] ?? 'Onbekende datum' }}
                                            </p>
                                            @if(isset($event['confidence']))
                                                <span class="text-xs text-neutral-500 dark:text-neutral-500">
                                                    {{ round($event['confidence'] * 100) }}% zekerheid
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Expanded details --}}
                                        @if($isExpanded)
                                            @if(isset($event['summary']))
                                                <div class="p-2.5 mt-2 text-sm bg-blue-50 rounded-md text-neutral-700 dark:bg-blue-900/10 dark:text-neutral-300">
                                                    {{ $event['summary'] }}
                                                </div>
                                            @endif
                                            @if(isset($event['actors']) && !empty($event['actors']))
                                                <div class="flex flex-wrap gap-1.5 mt-2">
                                                    <span class="text-xs font-medium text-neutral-600 dark:text-neutral-400">Betrokkenen:</span>
                                                    @foreach($event['actors'] as $actor)
                                                        <span class="px-2 py-0.5 text-xs rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                                                            {{ $actor }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif


                                        @endif
                                    </div>
                                    @if($hasDetails)
                                        <div class="flex-shrink-0 pt-0.5">
                                            <svg class="w-4 h-4 text-neutral-400 transition-transform duration-200 {{ $isExpanded ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    @endif
                                @if($hasDetails)
                                    </button>
                                @else
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p class="py-8 text-sm text-center text-neutral-600 dark:text-neutral-400">
            Nog geen timeline events beschikbaar
        </p>
    @endif
</div>

