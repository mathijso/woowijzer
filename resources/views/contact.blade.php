<x-app-layout>
    <x-slot name="title">Contact - woohub</x-slot>

    <div class="min-h-screen bg-white">
        <!-- Hero Section -->
        <section class="px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="text-center">
                    <h1 class="mb-6 text-4xl font-bold text-rijksblauw">
                        Contact
                    </h1>
                    <p class="mx-auto max-w-3xl text-xl text-rijksgrijs-6">
                        Heeft u vragen over woohub of wilt u feedback geven?
                        Neem gerust contact met ons op.
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-20 bg-rijksgrijs-1">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid gap-12 md:grid-cols-2">
                    <div>
                        <h2 class="mb-6 text-3xl font-bold text-rijksblauw">
                            Neem contact op
                        </h2>
                        <p class="mb-6 text-lg text-rijksgrijs-6">
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
                        <h2 class="mb-6 text-3xl font-bold text-rijksblauw">
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
