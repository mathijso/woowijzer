<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'woohub - Woo-documenten begrijpelijk maken' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-white h-full font-rijks text-black antialiased">
    <div class="min-h-full">
            <div class="bg-white m-0 mx-auto p-0">
                <div class="flex justify-center items-center m-0 p-0">
                    <div class="flex justify-center items-center m-0 p-0 logo">
                        <div class="m-0 p-0 logo__wrapper">
                            <figure class="flex items-center m-0 p-0 logo__figure">
                                <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                    alt="Logo Rijksoverheid" class="m-0 p-0 w-auto h-20">
                                <figcaption class="m-0 ml-3 p-0 logo__text">
                                    <span
                                        class="m-0 p-0 font-bold text-rijksblauw text-left logo__sender">Rijksoverheid</span>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>


        <!-- Navigation Bar -->
        <header class="bg-rijksblauw">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="flex justify-between items-center h-16">
                    <!-- Home Link -->
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}"
                            class="font-medium text-white hover:text-rijkscyaan transition-colors duration-200">
                            Home
                        </a>
                    </div>

                    <!-- Main Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('about') }}" class="flex items-center text-white hover:text-rijkscyaan transition-colors duration-200">
                            Over woohub
                        </a>
                        <a href="{{ route('contact') }}"
                            class="flex items-center text-white hover:text-rijkscyaan transition-colors duration-200">
                            Contact
                        </a>

                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="hover:bg-white px-4 py-2 border border-white rounded-md font-medium text-white hover:text-rijksblauw transition-all duration-200">
                                Mijn Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="hover:bg-white px-4 py-2 border border-white rounded-md font-medium text-white hover:text-rijksblauw transition-all duration-200">
                                Inloggen
                            </a>
                        @endauth
                    </nav>


                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" id="mobile-menu-button"
                            class="focus:outline-none focus:ring-2 focus:ring-rijkscyaan focus:ring-offset-2 text-white hover:text-rijkscyaan">
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
            <div id="mobile-menu" class="hidden md:hidden bg-rijksblauw border-rijkscyaan border-t">
                <div class="space-y-1 px-2 pt-2 pb-3">
                    <a href="{{ route('welcome') }}"
                        class="block hover:bg-rijkscyaan px-3 py-2 text-white hover:text-rijkscyaan transition-colors duration-200">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                        class="block hover:bg-rijkscyaan px-3 py-2 text-white hover:text-rijkscyaan transition-colors duration-200">
                        Over woohub
                    </a>
                    <a href="{{ route('document.summarize') }}"
                        class="block bg-rijkscyaan hover:bg-rijkscyaan-600 hover:shadow-md px-3 py-2 rounded-md font-medium text-white transition-all duration-200">
                        Document Samenvatten
                    </a>
                    <a href="{{ route('contact') }}"
                        class="block hover:bg-rijkscyaan px-3 py-2 text-white hover:text-rijkscyaan transition-colors duration-200">
                        Contact
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="block hover:bg-white px-3 py-2 border border-white rounded-md font-medium text-white hover:text-rijksblauw transition-all duration-200">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="block hover:bg-white px-3 py-2 border border-white rounded-md font-medium text-white hover:text-rijksblauw transition-all duration-200">
                            Inloggen
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-rijksgrijs-1 border-rijksgrijs-2 border-t">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-7xl">
                <div class="gap-8 grid md:grid-cols-2">
                    <!-- Hackathon Section -->
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="bg-white mr-3 p-2 rounded-lg">
                                <img src="{{ asset('images/hackathon.svg') }}" alt="Hackathon Open Overheid Terminal WOO" class="w-auto h-8">
                            </div>
                        </div>
                        <p class="text-rijksgrijs-6 text-sm">
                            Een initiatief geïnspireerd door Hackathon Open Overheid 'Terminal WOO'
                            op 31 oktober en 1 november 2025 in de Fokker Terminal in Den Haag.
                        </p>
                    </div>

                    <!-- Bedrijven Section -->
                    <div class="text-left md:text-right">
                        <h3 class="mb-4 font-bold text-rijksblauw text-lg">
                            Ontwikkeld door
                        </h3>
                        <div class="flex md:flex-row flex-col md:justify-end items-start md:items-center md:space-x-6 space-y-3 md:space-y-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/techletes_logo.jpg') }}"
                                     alt="Techletes.ai logo"
                                     class="mr-2 w-auto h-8 object-contain">
                                <a href="https://www.techletes.ai/"
                                   class="font-medium text-rijksblauw hover:text-rijkscyaan text-sm hover:underline">
                                    Techletes.ai
                                </a>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('images/oggel-codelabs_logo.svg') }}"
                                     alt="Oggel Codelabs logo"
                                     class="mr-2 w-auto h-8 object-contain">
                                <a href="https://oggel-codelabs.nl/"
                                   class="font-medium text-rijksblauw hover:text-rijkscyaan text-sm hover:underline">
                                    Oggel Codelabs
                                </a>
                            </div>
                        </div>
                        <p class="mt-4 text-rijksgrijs-5 text-xs">
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
