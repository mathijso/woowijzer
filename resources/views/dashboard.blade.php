<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        {{-- Welkomstbericht --}}
        <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-neutral-800">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">
                Welkom terug, {{ Auth::user()->name }}
            </h1>
            <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
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
            <div class="rounded-xl bg-white p-8 text-center shadow-sm dark:bg-neutral-800">
                <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Welkom</h2>
                <p class="mt-2 text-neutral-600 dark:text-neutral-400">
                    U ontvangt een e-mail wanneer er een verzoek tot het uploaden van documenten voor u klaarstaat.
                </p>
            </div>
        @endif
    </div>
</x-layouts.app>
