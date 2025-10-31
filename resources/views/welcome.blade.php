<x-app-layout>
    <x-slot name="title">woohub - Woo-documenten begrijpelijk maken</x-slot>

    <div class="min-h-screen bg-white">
        <!-- Hero Section - Rijksoverheid Style -->

        <section class="relative bg-white">
            <!-- Hero Image -->
            <div
                class="relative h-120"
                style="background-image: url('{{ asset('images/den-haag.jpg') }}'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 opacity-40 bg-rijksblauw"></div>
                <div class="flex relative items-center h-full">
                    <div class="px-4 mx-auto w-full max-w-7xl sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 gap-8 items-center lg:grid-cols-2">
                            <!-- Content -->
                            <div class="text-white">
                                <h1 class="mb-6 text-4xl font-bold lg:text-6xl">
                                    WooHub
                                </h1>
                                <p class="mb-8 text-xl font-medium lg:text-2xl">
                                    Maak Woo-documenten toegankelijk en begrijpelijk
                                </p>
                                <p class="mb-8 text-lg opacity-90">
                                    woohub helpt u om complexe Woo-documenten te begrijpen door ze samen te vatten,
                                    te visualiseren en inzichtelijk te presenteren.
                                </p>
                                <div class="flex flex-col gap-4 sm:flex-row">
                                    <a href="{{ route('document.summarize') }}"
                                       class="inline-flex items-center px-8 py-4 font-medium bg-white rounded-lg transition-colors duration-200 text-rijksblauw hover:bg-rijksgrijs-1">
                                        Document Samenvatten
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                    <a href="{{ route('about') }}"
                                       class="inline-flex items-center px-8 py-4 font-medium text-white rounded-lg border-2 border-white transition-colors duration-200 hover:bg-white hover:text-rijksblauw">
                                        Meer informatie
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- woohub Propositie Section -->
        <section class="py-20 bg-white">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <h3 class="mb-6 text-3xl font-bold text-rijksblauw">
                        Van open naar écht begrijpelijk
                    </h3>
                    <p class="mx-auto max-w-4xl text-xl leading-relaxed text-rijksgrijs-6">
                        Iedereen heeft recht op informatie van de overheid. Dat is het idee achter de Wet open overheid, de Woo.
                        Maar wie ooit een Woo-document heeft geopend, weet dat "open" nog niet betekent dat het ook begrijpelijk is.
                    </p>
                </div>

                <div class="grid gap-12 items-center mb-16 lg:grid-cols-2">
                    <div>
                        <h4 class="mb-6 text-2xl font-bold text-rijksblauw">
                            Het probleem
                        </h4>
                        <p class="mb-4 text-lg text-rijksgrijs-6">
                            De meeste Woo-documenten zijn juridisch, complex en soms honderden pagina's lang.
                            Transparantie stopt dus vaak bij de drempel van de taal.
                        </p>
                        <p class="text-lg text-rijksgrijs-6">
                            Burgers, journalisten en onderzoekers worstelen met ambtelijke taal en complexe besluitvorming.
                            De informatie is er wel, maar niet toegankelijk.
                        </p>
                    </div>
                    <div class="p-8 rounded-lg border bg-rijksgrijs-1 border-rijksgrijs-2">
                        <h5 class="mb-4 text-lg font-bold text-rijksblauw">Typische Woo-documenten</h5>
                        <ul class="space-y-3 text-rijksgrijs-6">
                            <li>• Besluiten van ministers en staatssecretarissen</li>
                            <li>• Kamerstukken en beleidsnota's</li>
                            <li>• Onderzoeksrapporten en evaluaties</li>
                            <li>• Juridische adviezen en procedures</li>
                            <li>• Financiële overzichten en begrotingen</li>
                    </ul>
                    </div>
                </div>

                <div class="grid gap-12 items-center lg:grid-cols-2">
                    <div class="p-8 rounded-lg border bg-rijksgrijs-1 border-rijksgrijs-2">
                        <h5 class="mb-4 text-lg font-bold text-rijksblauw">woohub oplossing</h5>
                        <p class="mb-4 text-rijksgrijs-6">
                            woohub is een slimme tool die Woo-documenten omzet in begrijpelijke, samengevatte en inzichtelijke informatie.
                        </p>
                        <ul class="space-y-3 text-rijksgrijs-6">
                            <li>• Controleert de leesbaarheid</li>
                            <li>• Vat samen op drie niveaus</li>
                            <li>• Toont besluitvorming overzichtelijk</li>
                            <li>• Maakt complexe informatie visueel</li>
                    </ul>
                    </div>
                    <div>
                        <h4 class="mb-6 text-2xl font-bold text-rijksblauw">
                            Hoe het werkt
                        </h4>
                        <p class="mb-4 text-lg text-rijksgrijs-6">
                            Gebruikers uploaden een document, waarna AI de tekst opsplitst, analyseert en overzichtelijk presenteert.
                            Zo wordt een stapel ambtelijke taal omgezet in heldere kennis.
                        </p>
                        <p class="text-lg text-rijksgrijs-6">
                            woohub is direct toepasbaar, gebruiksvriendelijk en innovatief. Burgers begrijpen sneller wat er speelt,
                            ambtenaren krijgen minder vragen, en journalisten vinden sneller de kern.
                        </p>
                    </div>
                </div>

                <div class="mt-16 text-center">
                    <div class="p-8 text-white rounded-lg bg-rijksblauw">
                        <h4 class="mb-4 text-2xl font-bold">
                            Openbaarheid krijgt pas betekenis als iedereen het kan begrijpen
                        </h4>
                        <p class="text-lg opacity-90">
                            woohub maakt de Woo niet alleen open, maar écht toegankelijk en menselijk.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-rijksblauw">
            <div class="px-4 mx-auto max-w-7xl text-center sm:px-6 lg:px-8">
                <h3 class="mb-4 text-3xl font-bold text-white">
                    Klaar om te beginnen?
                </h3>
                <p class="mx-auto mb-8 max-w-2xl text-xl text-white">
                    Upload uw eerste Woo-document en ervaar hoe eenvoudig het is om complexe informatie te begrijpen.
                </p>
                <a href="{{ route('document.summarize') }}"
                   class="inline-flex items-center px-8 py-4 font-medium bg-white rounded-lg transition-colors duration-200 text-rijksblauw hover:bg-rijksgrijs-1">
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Start nu met samenvatten
                </a>
                </div>
        </section>
        </div>
</x-app-layout>
