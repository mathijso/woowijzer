<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pagina niet gevonden - woohub</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-rijks bg-white text-black antialiased">
    <div class="min-h-full">
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
                            Over woohub
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
        </header>

        <!-- Error Content -->
        <main class="flex-1">
            <div class="min-h-screen bg-white">
                <section class="py-16 px-4 sm:px-6 lg:px-8">
                    <div class="max-w-7xl mx-auto">
                        <div class="text-center">
                            <!-- Error Code -->
                            <div class="mb-8">
                                <h1 class="text-9xl font-bold text-rijksblauw mb-4">404</h1>
                                <div class="w-24 h-1 bg-rijksblauw mx-auto mb-8"></div>
                            </div>

                            <!-- Error Message -->
                            <h2 class="text-3xl font-bold text-rijksblauw mb-4">
                                Pagina niet gevonden
                            </h2>
                            <p class="text-lg text-rijksgrijs-6 max-w-3xl mx-auto mb-8">
                                De pagina die u zoekt bestaat niet of is verplaatst.
                                Controleer de URL of ga terug naar de hoofdpagina.
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                                <a href="{{ route('welcome') }}"
                                   class="inline-flex items-center px-6 py-3 bg-rijksblauw text-white font-medium rounded-md hover:bg-rijkscyaan transition-colors duration-200">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Naar hoofdpagina
                                </a>
                                <a href="{{ route('document.summarize') }}"
                                   class="inline-flex items-center px-6 py-3 border border-rijksblauw text-rijksblauw font-medium rounded-md hover:bg-rijksblauw hover:text-white transition-colors duration-200">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Document samenvatten
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Help Section -->
                <section class="py-16 bg-rijksgrijs-1">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center">
                            <h3 class="text-xl font-bold text-rijksblauw mb-4">
                                Hulp nodig?
                            </h3>
                            <p class="text-rijksgrijs-6 mb-6">
                                Als u denkt dat dit een fout is, neem dan contact met ons op.
                            </p>
                            <a href="{{ route('contact') }}"
                               class="inline-flex items-center text-rijksblauw hover:text-rijkscyaan hover:underline font-medium">
                                Contact opnemen
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
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
                    <div class="md:text-right text-left">
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
</body>

</html>
