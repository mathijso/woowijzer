<x-app-layout>
    <x-slot name="title">Over woohub - Woo-documenten begrijpelijk maken</x-slot>

    <div class="min-h-screen bg-white">
        <!-- Hero Section -->
        <section class="py-16 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-rijksblauw mb-4">
                        Over woohub
                    </h1>
                    <p class="text-lg text-rijksgrijs-6 max-w-3xl mx-auto">
                        woohub is een initiatief geïnspireerd door de Wet open overheid (Woo)
                        om complexe overheidsdocumenten toegankelijk en begrijpelijk te maken voor iedereen.
                    </p>
                </div>
            </div>
        </section>

        <!-- Hackathon Section -->
        <section class="py-16 bg-rijksgrijs-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <div class="flex justify-center mb-6">
                        <div class="bg-white rounded-lg p-3">
                        <img src="{{ asset('images/hackathon.svg') }}" alt="Hackathon Open Overheid Terminal WOO" class="h-16 w-auto">
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-rijksblauw mb-4">
                        Ontstaan uit Hackathon Open Overheid 'Terminal WOO'
                    </h2>
                    <p class="text-lg text-rijksgrijs-6 max-w-4xl mx-auto">
                        woohub is ontstaan tijdens de Hackathon Open Overheid 'Terminal WOO' op 31 oktober en 1 november 2025
                        in de Fokker Terminal in Den Haag. Deze hackathon richtte zich op het ondersteunen van het Woo-proces
                        en het maken van overheidsinformatie begrijpelijker.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-12">
                    <div>
                        <h3 class="text-xl font-bold text-rijksblauw mb-4">
                            De uitdaging
                        </h3>
                        <p class="text-rijksgrijs-6 mb-4">
                            De hackathon daagde teams uit om technologische oplossingen te bedenken die het Woo-proces versnellen,
                            de informatievoorziening begrijpelijker maken en de werklast voor ambtenaren verlichten.
                        </p>
                        <p class="text-rijksgrijs-6">
                            Het doel was om met slimme technologie het Woo-proces te ondersteunen, informatie proactief openbaar
                            te maken en samen te bouwen aan een transparantere overheid.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-rijksblauw mb-4">
                            Onze missie
                        </h3>
                        <p class="text-rijksgrijs-6 mb-4">
                            Wij geloven dat overheidsinformatie toegankelijk moet zijn voor iedereen.
                            Door gebruik te maken van geavanceerde AI-technologie maken wij complexe
                            Woo-documenten begrijpelijk en inzichtelijk.
                        </p>
                        <p class="text-rijksgrijs-6">
                            Onze tool helpt burgers, journalisten, onderzoekers en andere belanghebbenden
                            om snel de kern van overheidsdocumenten te begrijpen.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-rijksblauw mb-4">
                            woohub oplossing
                        </h3>
                        <p class="text-rijksgrijs-6 mb-4">
                            Ons team ontwikkelde woohub als antwoord op de uitdaging om Woo-documenten begrijpelijker te maken.
                        </p>
                        <ul class="space-y-2 text-rijksgrijs-6">
                            <li>• Controleert de leesbaarheid van documenten</li>
                            <li>• Vat samen op drie niveaus</li>
                            <li>• Toont besluitvorming overzichtelijk</li>
                            <li>• Maakt complexe informatie visueel</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        <!-- Ontwikkelaars Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold text-rijksblauw mb-4">
                        Ontwikkeld door
                    </h2>
                    <p class="text-lg text-rijksgrijs-6 max-w-3xl mx-auto">
                        woohub is ontwikkeld door een samenwerking tussen twee innovatieve bedrijven
                        die gespecialiseerd zijn in technologie en digitale transformatie.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <div class="mb-4">
                            <img src="{{ asset('images/techletes_logo.jpg') }}"
                                 alt="Techletes.ai logo"
                                 class="h-12 w-auto object-contain">
                        </div>
                        <h3 class="text-xl font-bold text-rijksblauw mb-4">
                            Techletes.ai
                        </h3>
                        <p class="text-rijksgrijs-6 mb-4">
                            Techletes.ai is gespecialiseerd in het ontwikkelen van slimme technologieën
                            die complexe problemen oplossen. Zij brengen expertise in AI en machine learning
                            naar woohub.
                        </p>
                        <a href="https://www.techletes.ai/"
                           class="inline-flex items-center text-rijksblauw hover:text-rijkscyaan hover:underline font-medium">
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
                                 class="h-12 w-auto object-contain">
                        </div>
                        <h3 class="text-xl font-bold text-rijksblauw mb-4">
                            Oggel Codelabs
                        </h3>
                        <p class="text-rijksgrijs-6 mb-4">
                            Oggel Codelabs is een innovatief softwareontwikkelingsbedrijf dat zich richt
                            op het creëren van gebruiksvriendelijke en toegankelijke digitale oplossingen
                            voor complexe maatschappelijke uitdagingen.
                        </p>
                        <a href="https://oggel-codelabs.nl/"
                           class="inline-flex items-center text-rijksblauw hover:text-rijkscyaan hover:underline font-medium">
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
