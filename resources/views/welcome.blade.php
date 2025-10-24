<x-app-layout>
    <x-slot name="title">WooWijzer - Woo-documenten begrijpelijk maken</x-slot>
    
    <div class="min-h-screen bg-white">
        <!-- Hero Section - Rijksoverheid Style -->

        <section class="relative bg-white">
            <!-- Hero Image -->
            <div 
                class="relative h-120"
                style="background-image: url('{{ asset('images/den-haag.jpg') }}'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-rijksblauw opacity-40"></div>
                <div class="relative h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                            <!-- Content -->
                            <div class="text-white">
                                <h1 class="text-4xl lg:text-6xl font-bold mb-6">
                                    WooWijzer
                                </h1>
                                <p class="text-xl lg:text-2xl mb-8 font-medium">
                                    Maak Woo-documenten toegankelijk en begrijpelijk
                                </p>
                                <p class="text-lg mb-8 opacity-90">
                                    WooWijzer helpt u om complexe Woo-documenten te begrijpen door ze samen te vatten, 
                                    te visualiseren en inzichtelijk te presenteren.
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="{{ route('document.summarize') }}" 
                                       class="inline-flex items-center px-8 py-4 bg-white text-rijksblauw font-medium rounded-lg hover:bg-rijksgrijs-1 transition-colors duration-200">
                                        Document Samenvatten
                                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('about') }}" 
                                       class="inline-flex items-center px-8 py-4 border-2 border-white text-white font-medium rounded-lg hover:bg-white hover:text-rijksblauw transition-colors duration-200">
                                        Meer informatie
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-rijksgrijs-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-3xl font-bold text-rijksblauw mb-4">
                        Waarom WooWijzer?
                    </h3>
                    <p class="text-lg text-rijksgrijs-6 max-w-2xl mx-auto">
                        Woo-documenten kunnen complex en moeilijk te begrijpen zijn. WooWijzer maakt ze toegankelijk voor iedereen.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-rijkscyaan rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                        </div>
                        <h4 class="text-xl font-bold text-rijksblauw mb-2">Automatische Samenvatting</h4>
                        <p class="text-rijksgrijs-6">
                            Upload uw Woo-document en ontvang een duidelijke, beknopte samenvatting van de belangrijkste punten.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-rijkscyaan rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                        </div>
                        <h4 class="text-xl font-bold text-rijksblauw mb-2">Visuele Inzichten</h4>
                        <p class="text-rijksgrijs-6">
                            Complexe informatie wordt omgezet naar duidelijke visualisaties en grafieken voor beter begrip.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-rijkscyaan rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                        </div>
                        <h4 class="text-xl font-bold text-rijksblauw mb-2">Veilig & Betrouwbaar</h4>
                        <p class="text-rijksgrijs-6">
                            Uw documenten worden veilig verwerkt volgens de hoogste privacy- en beveiligingsstandaarden.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works Section -->
        <section class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-3xl font-bold text-rijksblauw mb-4">
                        Hoe werkt het?
                    </h3>
                    <p class="text-lg text-rijksgrijs-6 max-w-2xl mx-auto">
                        In drie eenvoudige stappen heeft u een begrijpelijke samenvatting van uw Woo-document.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-rijksblauw text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            1
                        </div>
                        <h4 class="text-xl font-bold text-rijksblauw mb-2">Upload Document</h4>
                        <p class="text-rijksgrijs-6">
                            Upload uw Woo-document (PDF of tekstbestand) via het uploadformulier.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-rijksblauw text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            2
                        </div>
                        <h4 class="text-xl font-bold text-rijksblauw mb-2">AI Verwerking</h4>
                        <p class="text-rijksgrijs-6">
                            Onze AI analyseert het document en extraheert de belangrijkste informatie.
                        </p>
        </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-12 h-12 bg-rijksblauw text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                            3
                        </div>
                        <h4 class="text-xl font-bold text-rijksblauw mb-2">Ontvang Samenvatting</h4>
                        <p class="text-rijksgrijs-6">
                            Bekijk de samenvatting, visualisaties en inzichten in een overzichtelijk dashboard.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-rijksblauw">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h3 class="text-3xl font-bold text-white mb-4">
                    Klaar om te beginnen?
                </h3>
                <p class="text-xl text-white mb-8 max-w-2xl mx-auto">
                    Upload uw eerste Woo-document en ervaar hoe eenvoudig het is om complexe informatie te begrijpen.
                </p>
                <a href="{{ route('document.summarize') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-rijksblauw font-medium rounded-lg hover:bg-rijksgrijs-1 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Start nu met samenvatten
                </a>
            </div>
        </section>
    </div>
</x-app-layout>