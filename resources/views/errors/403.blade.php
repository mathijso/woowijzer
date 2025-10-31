<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Toegang geweigerd - woohub</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased text-black bg-white font-rijks">
    <div class="min-h-full">
        <!-- Rijksoverheid Top Bar -->
        <div class="px-4 mx-auto bg-white sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-26">
                <!-- Rijksoverheid Logo -->
                <div class="flex justify-center items-center logo">
                    <div class="logo__wrapper">
                        <figure class="flex items-center logo__figure">
                            <img src="{{ asset('images/beeldmerk-rijksoverheid-desktop.svg') }}"
                                alt="Logo Rijksoverheid" class="w-auto h-20">
                            <figcaption class="ml-3 logo__text">
                                <span class="font-bold text-left logo__sender text-rijksblauw">Rijksoverheid</span>
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



                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button"
                            class="text-white hover:text-rijkscyaan focus:outline-none focus:ring-2 focus:ring-rijkscyaan focus:ring-offset-2">
                            <span class="sr-only">Open main menu</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
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
                <section class="px-4 py-16 sm:px-6 lg:px-8">
                    <div class="mx-auto max-w-7xl">
                        <div class="text-center">
                            <!-- Error Code -->
                            <div class="mb-8">
                                <h1 class="mb-4 text-9xl font-bold text-rijksblauw">403</h1>
                                <div class="mx-auto mb-8 w-24 h-1 bg-rijksblauw"></div>
                            </div>

                            <!-- Error Message -->
                            <h2 class="mb-4 text-3xl font-bold text-rijksblauw">
                                Toegang geweigerd
                            </h2>
                            <p class="mx-auto mb-8 max-w-3xl text-lg text-rijksgrijs-6">
                                U heeft geen toegang tot deze pagina. Mogelijk moet u inloggen
                                of heeft u niet de juiste rechten om deze content te bekijken.
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-4 justify-center items-center sm:flex-row">
                                <a href="{{ route('welcome') }}"
                                   class="inline-flex items-center px-6 py-3 font-medium text-white rounded-md transition-colors duration-200 bg-rijksblauw hover:bg-rijkscyaan">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Naar hoofdpagina
                                </a>
                                @guest
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-6 py-3 font-medium rounded-md border transition-colors duration-200 border-rijksblauw text-rijksblauw hover:bg-rijksblauw hover:text-white">
                                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Inloggen
                                </a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Help Section -->
                <section class="py-16 bg-rijksgrijs-1">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="text-center">
                            <h3 class="mb-4 text-xl font-bold text-rijksblauw">
                                Mogelijke oplossingen
                            </h3>
                            <div class="grid gap-6 text-left md:grid-cols-3">
                                <div>
                                    <h4 class="mb-2 font-semibold text-rijksblauw">Controleer uw inloggegevens</h4>
                                    <p class="text-sm text-rijksgrijs-6">
                                        Zorg ervoor dat u bent ingelogd met de juiste account.
                                    </p>
                                </div>
                                <div>
                                    <h4 class="mb-2 font-semibold text-rijksblauw">Controleer uw rechten</h4>
                                    <p class="text-sm text-rijksgrijs-6">
                                        Mogelijk heeft uw account niet de juiste rechten voor deze pagina.
                                    </p>
                                </div>
                                <div>
                                    <h4 class="mb-2 font-semibold text-rijksblauw">Neem contact op</h4>
                                    <p class="text-sm text-rijksgrijs-6">
                                        Als u denkt dat dit een fout is, neem dan contact met ons op.
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('contact') }}"
                                   class="inline-flex items-center font-medium text-rijksblauw hover:text-rijkscyaan hover:underline">
                                    Contact opnemen
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
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
                            <h3 class="text-lg font-bold text-rijksblauw">
                                Hackathon Open Overheid
                            </h3>
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
</body>

</html>
