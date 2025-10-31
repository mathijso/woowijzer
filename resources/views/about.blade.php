<x-app-layout>
    <x-slot name="title">Over woohub - Woo-documenten begrijpelijk maken</x-slot>

    <div class="bg-white min-h-screen">
        <!-- Hero Section -->
        <section class="px-4 sm:px-6 lg:px-8 py-16">
            <div class="mx-auto max-w-7xl">
                <div class="text-center">
                    <h1 class="mb-4 font-bold text-rijksblauw text-3xl">
                        Over woohub
                    </h1>
                    <p class="mx-auto max-w-3xl text-rijksgrijs-6 text-lg">
                        woohub is een initiatief geïnspireerd door de Wet open overheid (Woo)
                        om complexe overheidsdocumenten toegankelijk en begrijpelijk te maken voor iedereen.
                    </p>
                </div>
            </div>
        </section>

        <!-- Hackathon Section -->
        <section class="bg-rijksgrijs-1 py-16">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="mb-12 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="bg-white p-3 rounded-lg">
                        <img src="{{ asset('images/hackathon.svg') }}" alt="Hackathon Open Overheid Terminal WOO" class="w-auto h-16">
                        </div>
                    </div>
                    <h2 class="mb-4 font-bold text-rijksblauw text-2xl">
                        Ontstaan uit Hackathon Open Overheid 'Terminal WOO'
                    </h2>
                    <p class="mx-auto max-w-4xl text-rijksgrijs-6 text-lg">
                        woohub is ontstaan tijdens de Hackathon Open Overheid 'Terminal WOO' op 31 oktober en 1 november 2025
                        in de Fokker Terminal in Den Haag. Deze hackathon richtte zich op het ondersteunen van het Woo-proces
                        en het maken van overheidsinformatie begrijpelijker.
                    </p>
                </div>

                <div class="gap-4 grid md:grid-cols-3 mb-12">
                    <div>
                        <h3 class="mb-4 font-bold text-rijksblauw text-xl">
                            De uitdaging
                        </h3>
                        <p class="mb-4 text-rijksgrijs-6">
                            De hackathon daagde teams uit om technologische oplossingen te bedenken die het Woo-proces versnellen,
                            de informatievoorziening begrijpelijker maken en de werklast voor ambtenaren verlichten.
                        </p>
                        <p class="text-rijksgrijs-6">
                            Het doel was om met slimme technologie het Woo-proces te ondersteunen, informatie proactief openbaar
                            te maken en samen te bouwen aan een transparantere overheid. woohub biedt hiervoor een complete 
                            workflow oplossing met geautomatiseerde verwerking en inzichtelijke visualisaties.
                        </p>
                    </div>

                    <div>
                        <h3 class="mb-4 font-bold text-rijksblauw text-xl">
                            Onze missie
                        </h3>
                        <p class="mb-4 text-rijksgrijs-6">
                            Wij geloven dat overheidsinformatie toegankelijk moet zijn voor iedereen.
                            woohub is een compleet systeem dat het hele Woo-proces ondersteunt: 
                            van het indienen van verzoeken door burgers, via de verwerking door case managers, 
                            tot het uploaden en verwerken van documenten.
                        </p>
                        <p class="text-rijksgrijs-6">
                            Met geavanceerde AI-technologie (WOO Insight API) maken wij complexe 
                            Woo-documenten begrijpelijk door tijdlijnen te extraheren, beslissingen samen te vatten 
                            en documenten automatisch aan vragen te koppelen.
                        </p>
                    </div>

                    <div>
                        <h3 class="mb-4 font-bold text-rijksblauw text-xl">
                            woohub oplossing
                        </h3>
                        <p class="mb-4 text-rijksgrijs-6">
                            Ons team ontwikkelde woohub als antwoord op de uitdaging om Woo-documenten begrijpelijker te maken.
                        </p>
                        <ul class="space-y-2 text-rijksgrijs-6">
                            <li>• Extraheert automatisch vragen uit Woo-verzoeken</li>
                            <li>• Verwerkt documenten met WOO Insight API</li>
                            <li>• Creëert tijdlijnen van gebeurtenissen en beslissingen</li>
                            <li>• Vat beslissingen samen in begrijpelijk Nederlands (B1-niveau)</li>
                            <li>• Koppelt documenten automatisch aan vragen</li>
                            <li>• Biedt real-time verwerkingsstatus tracking</li>
                            <li>• Ondersteunt het complete Woo-proces workflow</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        <!-- Ontwikkelaars Section -->
        <section class="bg-white py-16">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 font-bold text-rijksblauw text-2xl">
                        Ontwikkeld door
                    </h2>
                    <p class="mx-auto max-w-3xl text-rijksgrijs-6 text-lg">
                        woohub is ontwikkeld door een samenwerking tussen twee innovatieve bedrijven
                        die gespecialiseerd zijn in technologie en digitale transformatie.
                    </p>
                </div>

                <div class="gap-8 grid md:grid-cols-2">
                    <div>
                        <div class="mb-4">
                            <img src="{{ asset('images/techletes_logo.jpg') }}"
                                 alt="Techletes.ai logo"
                                 class="w-auto h-12 object-contain">
                        </div>
                        <h3 class="mb-4 font-bold text-rijksblauw text-xl">
                            Techletes.ai
                        </h3>
                        <p class="mb-4 text-rijksgrijs-6">
                            Techletes.ai is gespecialiseerd in het ontwikkelen van slimme technologieën
                            die complexe problemen oplossen. Zij brengen expertise in AI en machine learning
                            naar woohub.
                        </p>
                        <a href="https://www.techletes.ai/"
                           class="inline-flex items-center font-medium text-rijksblauw hover:text-rijkscyaan hover:underline">
                            Bezoek Techletes.ai
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>

                    <div>
                        <div class="mb-4">
                            <img src="{{ asset('images/oggel-codelabs_logo.svg') }}"
                                 alt="Oggel Codelabs logo"
                                 class="w-auto h-12 object-contain">
                        </div>
                        <h3 class="mb-4 font-bold text-rijksblauw text-xl">
                            Oggel Codelabs
                        </h3>
                        <p class="mb-4 text-rijksgrijs-6">
                            Oggel Codelabs is een innovatief softwareontwikkelingsbedrijf dat zich richt
                            op het creëren van gebruiksvriendelijke en toegankelijke digitale oplossingen
                            voor complexe maatschappelijke uitdagingen.
                        </p>
                        <a href="https://oggel-codelabs.nl/"
                           class="inline-flex items-center font-medium text-rijksblauw hover:text-rijkscyaan hover:underline">
                            Bezoek Oggel Codelabs
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
