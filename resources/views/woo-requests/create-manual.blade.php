<x-layouts.app title="Handmatig WOO-verzoek aanmaken">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('woo-requests.index') }}" 
               class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Terug naar overzicht
            </a>
        </div>

        <div class="p-8 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Handmatig WOO-verzoek aanmaken</h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                    Maak een WOO-verzoek aan zonder document upload. U kunt direct vragen toevoegen aan uw verzoek.
                </p>
            </div>

            <form action="{{ route('woo-requests.store-manual') }}" method="POST" class="space-y-6">
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
                           class="block w-full px-4 py-2 mt-1 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
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
                              class="block w-full px-4 py-2 mt-1 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                              placeholder="Geef een korte toelichting op uw verzoek...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Questions Section --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-neutral-900 dark:text-white">
                            Vragen (optioneel)
                        </label>
                        <button type="button" 
                                id="add-question-btn"
                                class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30">
                            <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Vraag toevoegen
                        </button>
                    </div>
                    
                    <div id="questions-container" class="space-y-3">
                        {{-- Initial question field --}}
                        <div class="question-item flex gap-2">
                            <input type="text" 
                                   name="questions[]" 
                                   class="block flex-1 px-4 py-2 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                                   placeholder="bijv. Welke documenten zijn er beschikbaar over...">
                            <button type="button" 
                                    class="remove-question-btn flex-shrink-0 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30"
                                    title="Verwijder vraag">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    @error('questions')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @error('questions.*')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Box --}}
                <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/10">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium">Let op:</p>
                            <ul class="mt-2 ml-4 space-y-1 list-disc">
                                <li>Dit verzoek wordt zonder document aangemaakt</li>
                                <li>U kunt later documenten uploaden via interne verzoeken</li>
                                <li>Vragen kunnen ook later nog worden toegevoegd of aangepast</li>
                                <li>Een case manager zal uw verzoek in behandeling nemen</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end gap-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                    <a href="{{ route('woo-requests.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border rounded-lg border-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-600 dark:hover:bg-neutral-700">
                        Annuleren
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-semibold text-white bg-rijksblauw rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Verzoek aanmaken
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('questions-container');
            const addBtn = document.getElementById('add-question-btn');
            
            // Add question field
            addBtn.addEventListener('click', function() {
                const questionItem = document.createElement('div');
                questionItem.className = 'question-item flex gap-2';
                questionItem.innerHTML = `
                    <input type="text" 
                           name="questions[]" 
                           class="block flex-1 px-4 py-2 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                           placeholder="bijv. Welke documenten zijn er beschikbaar over...">
                    <button type="button" 
                            class="remove-question-btn flex-shrink-0 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30"
                            title="Verwijder vraag">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                container.appendChild(questionItem);
                updateRemoveButtons();
            });
            
            // Remove question field (event delegation)
            container.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-question-btn');
                if (removeBtn) {
                    const questionItems = container.querySelectorAll('.question-item');
                    if (questionItems.length > 1) {
                        removeBtn.closest('.question-item').remove();
                        updateRemoveButtons();
                    }
                }
            });
            
            // Update visibility of remove buttons
            function updateRemoveButtons() {
                const questionItems = container.querySelectorAll('.question-item');
                const removeButtons = container.querySelectorAll('.remove-question-btn');
                
                // Hide remove button if only one question field exists
                if (questionItems.length === 1) {
                    removeButtons.forEach(btn => btn.style.display = 'none');
                } else {
                    removeButtons.forEach(btn => btn.style.display = 'block');
                }
            }
            
            // Initial check
            updateRemoveButtons();
        });
    </script>
    @endpush
</x-layouts.app>

