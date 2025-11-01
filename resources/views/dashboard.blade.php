<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col flex-1 gap-6 mx-auto w-full max-w-7xl h-full">
        {{-- Welkomstbericht --}}
        <div class="bg-white dark:bg-neutral-800 shadow-sm p-6 rounded-xl">
            <h1 class="font-bold text-neutral-900 dark:text-white text-2xl">
                Welkom terug, {{ Auth::user()->name }}
            </h1>
            <p class="mt-1 text-neutral-600 dark:text-neutral-400 text-sm">
                @if(Auth::user()->isBurger())
                    Hier kunt u uw WOO-verzoeken bekijken en beheren.
                @elseif(Auth::user()->isCaseManager())
                    Overzicht van alle WOO-verzoeken en cases die onder uw beheer vallen.
                @endif
            </p>
        </div>

        @if(Auth::user()->isBurger())
            @include('dashboards.burger')
        @elseif(Auth::user()->isCaseManager())
            @include('dashboards.case-manager')
        @else
            {{-- Colleague view --}}
            <div class="bg-white dark:bg-neutral-800 shadow-sm p-8 rounded-xl text-center">
                <h2 class="font-semibold text-neutral-900 dark:text-white text-lg">Welkom</h2>
                <p class="mt-2 text-neutral-600 dark:text-neutral-400">
                    U ontvangt een e-mail wanneer er een verzoek tot het uploaden van documenten voor u klaarstaat.
                </p>
            </div>
        @endif
    </div>
</x-layouts.app>
