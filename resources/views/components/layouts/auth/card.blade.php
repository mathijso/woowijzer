<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <div class="flex items-center justify-center mb-2">
                        <div class="logo flex items-center justify-center">
                            <div class="logo__wrapper">
                                <figure class="logo__figure flex items-center">
                                    <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                        alt="Logo Rijksoverheid" class="h-16 w-auto">
                                    <figcaption class="logo__text ml-3">
                                        <span class="logo__sender text-rijksblauw font-bold text-left">Rijksoverheid</span>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="flex flex-col gap-6">
                    <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
