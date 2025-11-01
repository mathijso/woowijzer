<x-layouts.app title="Nieuw WOO-verzoek">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('woo-requests.index') }}"
               class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Terug naar overzicht
            </a>
        </div>

        <div class="p-8 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Nieuw WOO-verzoek indienen</h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                    Vul onderstaand formulier in om een WOO-verzoek (Wet open overheid) in te dienen. Upload het PDF-document met uw verzoek.
                </p>
            </div>

            <form action="{{ route('woo-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Titel <span class="text-red-600">*</span>
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           required
                           class="block px-4 py-2 mt-1 w-full rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                           placeholder="Bijv. Verzoek tot openbaarmaking van documenten over klimaatbeleid">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Beschrijving (optioneel)
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="block px-4 py-2 mt-1 w-full rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                              placeholder="Geef een korte toelichting op uw verzoek...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Document Upload --}}
                <div>
                    <label for="document" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        WOO-verzoek document (PDF) <span class="text-red-600">*</span>
                    </label>
                    <div class="mt-1">
                        <div class="flex justify-center px-6 pt-5 pb-6 rounded-lg border-2 border-dashed border-neutral-300 dark:border-neutral-700 hover:border-blue-400">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto w-12 h-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-neutral-600 dark:text-neutral-400">
                                    <label for="document" class="relative font-medium text-rijksblauw rounded-md cursor-pointer hover:text-blue-500">
                                        <span>Upload een bestand</span>
                                        <input id="document" name="document" type="file" accept=".pdf" required class="sr-only">
                                    </label>
                                    <p class="pl-1">of sleep het hier naartoe</p>
                                </div>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                    Alleen PDF bestanden tot {{ config('woo.max_upload_size_mb', 50) }}MB
                                </p>
                            </div>
                        </div>
                    </div>
                    <div id="file-list" class="mt-3 space-y-2"></div>
                    @error('document')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Box --}}
                <div class="p-4 bg-blue-50 rounded-lg dark:bg-blue-900/10">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium">Let op:</p>
                            <ul class="mt-2 ml-4 space-y-1 list-disc">
                                <li>Uw document wordt na indienen automatisch verwerkt</li>
                                <li>Vragen worden automatisch geëxtraheerd uit het document</li>
                                <li>U kunt de verwerkingsstatus volgen na het indienen</li>
                                <li>Een case manager zal uw verzoek in behandeling nemen</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex gap-4 justify-end pt-4 border-t border-neutral-200 dark:border-neutral-700">
                    <a href="{{ route('woo-requests.index') }}"
                       class="px-4 py-2 text-sm font-medium bg-white rounded-lg border text-neutral-700 border-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-600 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Annuleren
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-sm font-semibold text-white bg-rijksblauw rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Verzoek indienen
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show selected file
        document.getElementById('document').addEventListener('change', function(e) {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';

            const file = e.target.files[0];
            if (file) {
                const div = document.createElement('div');
                div.className = 'flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg';
                div.innerHTML = `
                    <svg class="mr-2 w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-green-700 dark:text-green-400">${file.name}</span>
                    <span class="ml-auto text-xs text-green-600 dark:text-green-400">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                `;
                fileList.appendChild(div);
            }
        });
    </script>
    @endpush
</x-layouts.app>

