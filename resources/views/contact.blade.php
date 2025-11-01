<x-app-layout>
    <x-slot name="title">Contact - woohub</x-slot>

    <div class="bg-white min-h-screen">
        <!-- Hero Section -->
        <section class="px-4 sm:px-6 lg:px-8 py-20">
            <div class="mx-auto max-w-7xl">
                <div class="text-center">
                    <h1 class="mb-6 font-bold text-rijksblauw text-4xl">
                        Contact
                    </h1>
                    <p class="mx-auto max-w-3xl text-rijksgrijs-6 text-xl">
                        Heeft u vragen over woohub of wilt u feedback geven?
                        Neem gerust contact met ons op.
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="bg-rijksgrijs-1 py-20">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
                <div class="gap-12 grid md:grid-cols-2">
                    <div>
                        <h2 class="mb-6 font-bold text-rijksblauw text-3xl">
                            Neem contact op
                        </h2>
                        <p class="mb-6 text-rijksgrijs-6 text-lg">
                            Voor vragen, suggesties of technische ondersteuning kunt u contact met ons opnemen.
                        </p>
                        <div class="space-y-4">

                            <div>
                                <h3 class="font-semibold text-rijksblauw">Oggel Codelabs</h3>
                                <p class="text-rijksgrijs-6">mathijs@oggel-codelabs.nl</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-rijksblauw">Techletes</h3>
                                <p class="text-rijksgrijs-6">info@techletes.ai</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-6 font-bold text-rijksblauw text-3xl">
                            Veelgestelde vragen
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-semibold text-rijksblauw">Is mijn document veilig?</h3>
                                <p class="text-rijksgrijs-6">Ja, wij hanteren de hoogste beveiligingsstandaarden en uw documenten worden veilig verwerkt.</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-rijksblauw">Welke bestandsformaten worden ondersteund?</h3>
                                <p class="text-rijksgrijs-6">We ondersteunen PDF, Word documenten (.doc, .docx), tekstbestanden (.txt) en afbeeldingen (.jpg, .jpeg, .png).</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-rijksblauw">Hoe lang duurt de verwerking?</h3>
                                <p class="text-rijksgrijs-6">Documenten worden asynchroon verwerkt met de WOO Insight API. De meeste documenten worden binnen enkele minuten verwerkt, maar complexe documenten kunnen langer duren. U kunt de verwerkingsstatus real-time volgen in het dashboard.</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-rijksblauw">Wat doet de WOO Insight API?</h3>
                                <p class="text-rijksgrijs-6">De WOO Insight API extraheert automatisch tijdlijnen van gebeurtenissen uit documenten, vat beslissingen samen in begrijpelijk Nederlands (B1-niveau) en analyseert documenten om ze automatisch aan vragen te kunnen koppelen.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
