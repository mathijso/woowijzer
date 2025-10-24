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
        <!-- Header -->
        <header class="bg-white border-b border-rijksgrijs-2">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}" class="flex items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-rijksblauw rounded flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">W</span>
                                </div>
                                <span class="text-xl font-bold text-rijksblauw">WooWijzer</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('welcome') }}" 
                           class="text-rijksblauw hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Home
                        </a>
                        <a href="{{ route('about') }}" 
                           class="text-rijksblauw hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Over WooWijzer
                        </a>
                        <a href="{{ route('document.summarize') }}" 
                           class="text-rijksblauw hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Document Samenvatten
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="text-rijksblauw hover:text-rijkscyaan hover:underline transition-colors duration-200">
                            Contact
                        </a>
                    </nav>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button" class="text-rijksblauw hover:text-rijkscyaan focus:outline-none focus:ring-2 focus:ring-rijkscyaan focus:ring-offset-2">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden border-t border-rijksgrijs-2 bg-rijksgrijs-1">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('welcome') }}" 
                       class="block px-3 py-2 text-rijksblauw hover:text-rijkscyaan hover:bg-white transition-colors duration-200">
                        Home
                    </a>
                    <a href="{{ route('about') }}" 
                       class="block px-3 py-2 text-rijksblauw hover:text-rijkscyaan hover:bg-white transition-colors duration-200">
                        Over WooWijzer
                    </a>
                    <a href="{{ route('document.summarize') }}" 
                       class="block px-3 py-2 text-rijksblauw hover:text-rijkscyaan hover:bg-white transition-colors duration-200">
                        Document Samenvatten
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="block px-3 py-2 text-rijksblauw hover:text-rijkscyaan hover:bg-white transition-colors duration-200">
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
                <div class="text-center">
                    <p class="text-rijksgrijs-6 text-sm">
                        Een initiatief geïnspireerd door de Wet open overheid
                    </p>
                    <p class="text-rijksgrijs-5 text-xs mt-2">
                        © {{ date('Y') }} WooWijzer. Alle rechten voorbehouden.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>