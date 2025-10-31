<x-app-layout>
    <x-slot name="title">woohub - Woo-documenten begrijpelijk maken</x-slot>

    <div class="bg-white min-h-screen">
        <!-- Hero Section - Rijksoverheid Style -->

        <section class="relative bg-white">
            <!-- Hero Image -->
            <div
                class="relative h-120"
                style="background-image: url('{{ asset('images/den-haag.jpg') }}'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-rijksblauw opacity-40"></div>
                <div class="relative flex items-center h-full">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8 w-full max-w-7xl">
                        <div class="items-center gap-8 grid grid-cols-1 lg:grid-cols-2">
                            <!-- Content -->
                            <div class="text-white">
                                <h1 class="mb-6 font-bold text-4xl lg:text-6xl">
                                    WooHub
                                </h1>
                                <p class="mb-8 font-medium text-xl lg:text-2xl">
                                    Maak Woo-documenten toegankelijk en begrijpelijk
                                </p>
                                <p class="opacity-90 mb-8 text-lg">
                                    woohub is een compleet systeem voor het beheren van Woo-verzoeken. Van het indienen van verzoeken tot het verwerken van documenten met AI-technologie die tijdlijnen extraheert, beslissingen samenvat en documenten automatisch koppelt aan vragen.
                                </p>
                                <div class="flex sm:flex-row flex-col gap-4">
                                    @auth
                                        <a href="{{ route('dashboard') }}"
                                           class="inline-flex items-center bg-white hover:bg-rijksblauw px-8 py-4 rounded-lg font-medium text-rijksblauw hover:text-white transition-colors duration-200">
                                            Naar Dashboard
                                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                           class="inline-flex items-center bg-white hover:bg-rijksblauw px-8 py-4 rounded-lg font-medium text-rijksblauw hover:text-white transition-colors duration-200">
                                            Inloggen
                                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @endauth
                                    <a href="{{ route('about') }}"
                                       class="inline-flex items-center hover:bg-white px-8 py-4 border-2 border-white rounded-lg font-medium text-white hover:text-rijksblauw transition-colors duration-200">
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
        <section class="bg-white py-20">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="mb-16 text-center">
                    <h3 class="mb-6 font-bold text-rijksblauw text-3xl">
                        Van open naar écht begrijpelijk
                    </h3>
                        <p class="mx-auto max-w-4xl text-rijksgrijs-6 text-xl leading-relaxed">
                        Iedereen heeft recht op informatie van de overheid. Dat is het idee achter de Wet open overheid, de Woo.
                        woohub ondersteunt het complete Woo-proces: van het indienen van verzoeken door burgers, via de verwerking door case managers, tot het uploaden van documenten door collega's. Met geavanceerde AI-technologie maken we Woo-documenten écht begrijpelijk.
                    </p>
                </div>

                <div class="items-center gap-12 grid lg:grid-cols-2 mb-16">
                    <div>
                        <h4 class="mb-6 font-bold text-rijksblauw text-2xl">
                            Het probleem
                        </h4>
                        <p class="mb-4 text-rijksgrijs-6 text-lg">
                            Het Woo-proces is complex: burgers dienen verzoeken in, case managers moeten vragen uit documenten halen,
                            collega's moeten relevante documenten vinden en uploaden, en uiteindelijk moeten alle informatie begrijpelijk
                            worden gepresenteerd.
                        </p>
                        <p class="text-rijksgrijs-6 text-lg">
                            Daarbij komt dat Woo-documenten vaak juridisch, complex en honderden pagina's lang zijn.
                            De informatie is er wel, maar niet gestructureerd en niet toegankelijk.
                        </p>
                    </div>
                    <div class="bg-rijksgrijs-1 p-8 border border-rijksgrijs-2 rounded-lg">
                        <h5 class="mb-4 font-bold text-rijksblauw text-lg">Typische Woo-documenten</h5>
                        <ul class="space-y-3 text-rijksgrijs-6">
                            <li>• Besluiten van ministers en staatssecretarissen</li>
                            <li>• Kamerstukken en beleidsnota's</li>
                            <li>• Onderzoeksrapporten en evaluaties</li>
                            <li>• Juridische adviezen en procedures</li>
                            <li>• Financiële overzichten en begrotingen</li>
                    </ul>
                    </div>
                </div>

                <div class="items-center gap-12 grid lg:grid-cols-2">
                    <div class="bg-rijksgrijs-1 p-8 border border-rijksgrijs-2 rounded-lg">
                        <h5 class="mb-4 font-bold text-rijksblauw text-lg">woohub oplossing</h5>
                        <p class="mb-4 text-rijksgrijs-6">
                            woohub is een compleet systeem dat het hele Woo-proces ondersteunt met geavanceerde AI-technologie.
                        </p>
                        <ul class="space-y-3 text-rijksgrijs-6">
                            <li>• Extraheert automatisch vragen uit Woo-verzoeken</li>
                            <li>• Verwerkt documenten met WOO Insight API</li>
                            <li>• Creëert tijdlijnen van gebeurtenissen</li>
                            <li>• Vat beslissingen samen in begrijpelijk Nederlands (B1-niveau)</li>
                            <li>• Koppelt documenten automatisch aan vragen</li>
                            <li>• Biedt overzichtelijke status tracking</li>
                    </ul>
                    </div>
                    <div>
                        <h4 class="mb-6 font-bold text-rijksblauw text-2xl">
                            Hoe het werkt
                        </h4>
                        <p class="mb-4 text-rijksgrijs-6 text-lg">
                            <strong>Voor burgers:</strong> Dien een Woo-verzoek in met een PDF. woohub extraheert automatisch de vragen
                            en u kunt de voortgang volgen via een persoonlijk dashboard.
                        </p>
                        <p class="mb-4 text-rijksgrijs-6 text-lg">
                            <strong>Voor case managers:</strong> Beheer alle verzoeken in één overzicht, wijs interne verzoeken toe aan
                            collega's en gebruik automatische koppeling om documenten aan vragen te koppelen.
                        </p>
                        <p class="text-rijksgrijs-6 text-lg">
                            <strong>Voor collega's:</strong> Ontvang een veilige upload-link via e-mail en upload documenten zonder in te loggen.
                            De WOO Insight API verwerkt documenten automatisch en extraheert tijdlijnen en beslissingen.
                        </p>
                    </div>
                </div>

                <div class="mt-16 text-center">
                    <div class="bg-rijksblauw p-8 rounded-lg text-white">
                        <h4 class="mb-4 font-bold text-2xl">
                            Openbaarheid krijgt pas betekenis als iedereen het kan begrijpen
                        </h4>
                        <p class="opacity-90 text-lg">
                            woohub maakt het Woo-proces niet alleen efficiënter, maar ook transparanter.
                            Met geautomatiseerde verwerking, tijdlijn visualisaties en begrijpelijke samenvattingen
                            wordt complexe overheidsinformatie écht toegankelijk voor iedereen.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-rijksblauw py-20">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl text-center">
                <h3 class="mb-4 font-bold text-white text-3xl">
                    Klaar om te beginnen?
                </h3>
                <p class="mx-auto mb-8 max-w-2xl text-white text-xl">
                    Dien uw eerste Woo-verzoek in of log in om het systeem te gebruiken.
                    Ervaar hoe woohub het complete Woo-proces ondersteunt met geavanceerde AI-technologie.
                </p>
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center bg-white hover:bg-rijksblauw px-8 py-4 rounded-lg font-medium text-rijksblauw hover:text-white transition-colors duration-200">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Naar Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center bg-white hover:bg-rijksblauw px-8 py-4 rounded-lg font-medium text-rijksblauw hover:text-white transition-colors duration-200">
                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Inloggen om te beginnen
                    </a>
                @endauth
                </div>
        </section>
        </div>
</x-app-layout>
