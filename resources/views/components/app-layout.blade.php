<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'WooWijzer - Woo-documenten begrijpelijk maken' }}</title>

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

<body class="h-full font-rijks bg-white text-black antialiased">
    <div class="min-h-full">
            <div class="mx-auto bg-white m-0 p-0">
                <div class="flex justify-center items-center  m-0 p-0">
                    <div class="logo flex items-center justify-center m-0 p-0">
                        <div class="logo__wrapper m-0 p-0">
                            <figure class="logo__figure flex items-center m-0 p-0">
                                <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                    alt="Logo Rijksoverheid" class="h-20 w-auto m-0 p-0">
                                <figcaption class="logo__text ml-3 m-0 p-0">
                                    <span
                                        class="logo__sender text-rijksblauw font-bold text-left m-0 p-0">Rijksoverheid</span>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        

        <!-- Navigation Bar -->
        <header class="bg-rijksblauw">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Home Link -->
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}"
                            class="text-white hover:text-rijkscyaan transition-colors duration-200 font-medium">
                            Home
                        </a>
                    </div>

                    <!-- Main Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('about') }}"
                            class="text-white hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Over WooWijzer
                        </a>
                        <a href="{{ route('document.summarize') }}"
                            class="text-white hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Document Samenvatten
                        </a>
                        <a href="{{ route('contact') }}"
                            class="text-white hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Contact
                        </a>
                    </nav>

                  
                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button"
                            class="text-white hover:text-rijkscyaan focus:outline-none focus:ring-2 focus:ring-rijkscyaan focus:ring-offset-2">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden border-t border-rijkscyaan bg-rijksblauw">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('welcome') }}"
                        class="block px-3 py-2 text-white hover:text-rijkscyaan hover:bg-rijkscyaan transition-colors duration-200">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                        class="block px-3 py-2 text-white hover:text-rijkscyaan hover:bg-rijkscyaan transition-colors duration-200">
                        Over WooWijzer
                    </a>
                    <a href="{{ route('document.summarize') }}"
                        class="block px-3 py-2 text-white hover:text-rijkscyaan hover:bg-rijkscyaan transition-colors duration-200">
                        Document Samenvatten
                    </a>
                    <a href="{{ route('contact') }}"
                        class="block px-3 py-2 text-white hover:text-rijkscyaan hover:bg-rijkscyaan transition-colors duration-200">
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
        <footer class="bg-rijksgrijs-1 border-t border-rijksgrijs-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Hackathon Section -->
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="bg-white rounded-lg p-2 mr-3">
                                <img src="{{ asset('images/hackathon.svg') }}" alt="Hackathon Open Overheid Terminal WOO" class="h-8 w-auto">
                            </div>
                            <h3 class="text-lg font-bold text-rijksblauw">
                                Hackathon Open Overheid
                            </h3>
                        </div>
                        <p class="text-rijksgrijs-6 text-sm">
                            Een initiatief geïnspireerd door Hackathon Open Overheid 'Terminal WOO' 
                            op 31 oktober en 1 november 2025 in de Fokker Terminal in Den Haag.
                        </p>
                    </div>

                    <!-- Bedrijven Section -->
                    <div class="md:text-right  text-left">
                        <h3 class="text-lg font-bold text-rijksblauw mb-4">
                            Ontwikkeld door
                        </h3>
                        <div class="flex flex-col items-start space-y-3 md:flex-row md:items-center md:justify-end md:space-x-6 md:space-y-0">
                            <div class="flex items-center">
                                <img src="{{ asset('images/techletes_logo.jpg') }}" 
                                     alt="Techletes.ai logo" 
                                     class="h-8 w-auto object-contain mr-2">
                                <a href="https://www.techletes.ai/" 
                                   class="text-rijksblauw hover:text-rijkscyaan hover:underline text-sm font-medium">
                                    Techletes.ai
                                </a>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('images/oggel-codelabs_logo.svg') }}" 
                                     alt="Oggel Codelabs logo" 
                                     class="h-8 w-auto object-contain mr-2">
                                <a href="https://oggel-codelabs.nl/" 
                                   class="text-rijksblauw hover:text-rijkscyaan hover:underline text-sm font-medium">
                                    Oggel Codelabs
                                </a>
                            </div>
                        </div>
                        <p class="text-rijksgrijs-5 text-xs mt-4">
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
