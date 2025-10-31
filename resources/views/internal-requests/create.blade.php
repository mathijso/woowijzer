<x-layouts.app title="Nieuw intern verzoek">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('cases.show', $wooRequest) }}" 
               class="inline-flex items-center text-sm text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white">
                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Terug naar case
            </a>
        </div>

        <div class="p-8 bg-white rounded-xl shadow-sm dark:bg-neutral-800">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Nieuw intern verzoek</h1>
                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                    Verstuur een verzoek naar een collega om documenten aan te leveren voor: <strong>{{ $wooRequest->title }}</strong>
                </p>
            </div>

            <form action="{{ route('internal-requests.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="woo_request_id" value="{{ $wooRequest->id }}">

                {{-- Colleague Email --}}
                <div>
                    <label for="colleague_email" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Email collega <span class="text-red-600">*</span>
                    </label>
                    <input type="email" 
                           name="colleague_email" 
                           id="colleague_email" 
                           value="{{ old('colleague_email') }}"
                           required
                           class="block w-full px-4 py-2 mt-1 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                           placeholder="collega@overheid.nl">
                    @error('colleague_email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Colleague Name --}}
                <div>
                    <label for="colleague_name" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Naam collega (optioneel)
                    </label>
                    <input type="text" 
                           name="colleague_name" 
                           id="colleague_name" 
                           value="{{ old('colleague_name') }}"
                           class="block w-full px-4 py-2 mt-1 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                           placeholder="Jan de Vries">
                    @error('colleague_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-900 dark:text-white">
                        Beschrijving <span class="text-red-600">*</span>
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="6"
                              required
                              class="block w-full px-4 py-2 mt-1 border rounded-lg border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                              placeholder="Geef een duidelijke beschrijving van welke documenten je nodig hebt en waarom...">{{ old('description') }}</textarea>
                    <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">
                        Deze beschrijving wordt getoond aan de collega bij het upload formulier
                    </p>
                    @error('description')
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
                            <p class="font-medium">Wat gebeurt er:</p>
                            <ul class="mt-2 ml-4 space-y-1 list-disc">
                                <li>De collega ontvangt een email met een unieke upload link</li>
                                <li>De link is {{ config('woo.upload_token_expiry_days', 28) }} dagen geldig</li>
                                <li>De collega kan meerdere keren documenten uploaden via deze link</li>
                                <li>U ontvangt een notificatie bij elke upload</li>
                                <li>Documenten worden automatisch verwerkt</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Questions Reference --}}
                @if($wooRequest->questions->count() > 0)
                <div class="p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                    <h3 class="text-sm font-medium text-neutral-900 dark:text-white mb-3">Vragen in dit WOO-verzoek:</h3>
                    <ul class="space-y-2 text-sm text-neutral-700 dark:text-neutral-300 list-decimal list-inside">
                        @foreach($wooRequest->questions as $question)
                            <li class="pl-2">{{ $question->question_text }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Submit Button --}}
                <div class="flex justify-end gap-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                    <a href="{{ route('cases.show', $wooRequest) }}" 
                       class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border rounded-lg border-neutral-300 hover:bg-neutral-50 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-600 dark:hover:bg-neutral-700">
                        Annuleren
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Verzoek versturen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

