<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <!-- Rijksoverheid Top Bar -->
        <div class="mx-auto bg-white px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-26">
                <!-- Rijksoverheid Logo -->
                <div class="logo flex items-center justify-center">
                    <div class="logo__wrapper">
                        <figure class="logo__figure flex items-center">
                            <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                alt="Logo Rijksoverheid" class="h-20 w-auto">
                            <figcaption class="logo__text ml-3">
                                <span class="logo__sender text-rijksblauw font-bold text-left">Rijksoverheid</span>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
                <div class="absolute inset-0 bg-neutral-900"></div>
                <!-- Logo is now outside this section and always at top -->

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <div class="flex items-center justify-center">
                            <div class="logo flex items-center justify-center">
                                <div class="logo__wrapper">
                                    <figure class="logo__figure flex items-center">
                                        <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                            alt="Logo Rijksoverheid" class="h-12 w-auto">
                                        <figcaption class="logo__text ml-3">
                                            <span class="logo__sender text-rijksblauw font-bold text-left">Rijksoverheid</span>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
