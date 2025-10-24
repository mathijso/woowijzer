<x-app-layout>
    <x-slot name="title">Document Samenvatten - WooWijzer</x-slot>
    
    <div class="min-h-screen bg-white">
        <!-- Hero Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-rijksblauw mb-6">
                        Document Samenvatten
                    </h1>
                    <p class="text-xl text-rijksgrijs-6 max-w-3xl mx-auto">
                        Upload uw Woo-document en ontvang een duidelijke, beknopte samenvatting 
                        van de belangrijkste punten en inzichten.
                    </p>
                </div>
            </div>
        </section>

        <!-- Document Upload Section -->
        <section class="py-20 ">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <code>
                    hier komt de document upload
                </code>
                @livewire('document-summarizer')
            </div>
        </section>
    </div>
</x-app-layout>
