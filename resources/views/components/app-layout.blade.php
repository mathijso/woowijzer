<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'woohub - Woo-documenten begrijpelijk maken' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full antialiased text-black bg-white font-rijks">
    <div class="min-h-full">
            <div class="p-0 m-0 mx-auto bg-white">
                <div class="flex justify-center items-center p-0 m-0">
                    <div class="flex justify-center items-center p-0 m-0 logo">
                        <div class="p-0 m-0 logo__wrapper">
                            <figure class="flex items-center p-0 m-0 logo__figure">
                                <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                    alt="Logo Rijksoverheid" class="p-0 m-0 w-auto h-20">
                                <figcaption class="p-0 m-0 ml-3 logo__text">
                                    <span
                                        class="p-0 m-0 font-bold text-left logo__sender text-rijksblauw">Rijksoverheid</span>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Navigation Bar -->
        <header class="bg-rijksblauw">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Home Link -->
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}"
                            class="font-medium text-white transition-colors duration-200 hover:text-rijkscyaan">
                            Home
                        </a>
                    </div>

                    <!-- Main Navigation -->
                    <nav class="hidden space-x-8 md:flex">

                        <a href="{{ route('document.summarize') }}"
                            class="px-4 py-2 font-medium text-white bg-white rounded-md transition-all duration-200 text-rijksblauw hover:bg-rijkscyaan-600 hover:shadow-md">
                            Document Samenvatten
                        </a>
                        <a href="{{ route('about') }}" class="text-white transition-colors duration-200 hover:text-rijkscyaan hover:underline">
                            Over woohub
                        </a>
                        <a href="{{ route('contact') }}"
                            class="text-white transition-colors duration-200 hover:text-rijkscyaan hover:underline">
                            Contact
                        </a>
                    </nav>


                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" id="mobile-menu-button"
                            class="text-white hover:text-rijkscyaan focus:outline-none focus:ring-2 focus:ring-rijkscyaan focus:ring-offset-2">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger icon -->
                            <svg id="hamburger-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <!-- Close icon -->
                            <svg id="close-icon" class="hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden border-t md:hidden border-rijkscyaan bg-rijksblauw">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('welcome') }}"
                        class="block px-3 py-2 text-white transition-colors duration-200 hover:text-rijkscyaan hover:bg-rijkscyaan">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                        class="block px-3 py-2 text-white transition-colors duration-200 hover:text-rijkscyaan hover:bg-rijkscyaan">
                        Over woohub
                    </a>
                    <a href="{{ route('document.summarize') }}"
                        class="block px-3 py-2 font-medium text-white rounded-md transition-all duration-200 bg-rijkscyaan hover:bg-rijkscyaan-600 hover:shadow-md">
                        Document Samenvatten
                    </a>
                    <a href="{{ route('contact') }}"
                        class="block px-3 py-2 text-white transition-colors duration-200 hover:text-rijkscyaan hover:bg-rijkscyaan">
                        Contact
                    </a>
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t bg-rijksgrijs-1 border-rijksgrijs-2">
            <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid gap-8 md:grid-cols-2">
                    <!-- Hackathon Section -->
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="p-2 mr-3 bg-white rounded-lg">
                                <img src="{{ asset('images/hackathon.svg') }}" alt="Hackathon Open Overheid Terminal WOO" class="w-auto h-8">
                            </div>
                        </div>
                        <p class="text-sm text-rijksgrijs-6">
                            Een initiatief geïnspireerd door Hackathon Open Overheid 'Terminal WOO'
                            op 31 oktober en 1 november 2025 in de Fokker Terminal in Den Haag.
                        </p>
                    </div>

                    <!-- Bedrijven Section -->
                    <div class="text-left md:text-right">
                        <h3 class="mb-4 text-lg font-bold text-rijksblauw">
                            Ontwikkeld door
                        </h3>
                        <div class="flex flex-col items-start space-y-3 md:flex-row md:items-center md:justify-end md:space-x-6 md:space-y-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/techletes_logo.jpg') }}"
                                     alt="Techletes.ai logo"
                                     class="object-contain mr-2 w-auto h-8">
                                <a href="https://www.techletes.ai/"
                                   class="text-sm font-medium text-rijksblauw hover:text-rijkscyaan hover:underline">
                                    Techletes.ai
                                </a>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('images/oggel-codelabs_logo.svg') }}"
                                     alt="Oggel Codelabs logo"
                                     class="object-contain mr-2 w-auto h-8">
                                <a href="https://oggel-codelabs.nl/"
                                   class="text-sm font-medium text-rijksblauw hover:text-rijkscyaan hover:underline">
                                    Oggel Codelabs
                                </a>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-rijksgrijs-5">
                            © {{ date('Y') }} Techlethes.ai en Oggel-codelabs. Alle rechten voorbehouden.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>
