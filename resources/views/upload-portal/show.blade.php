<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documenten Uploaden - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 dark:bg-neutral-900">
    <div class="py-12 min-h-screen">
        <div class="px-4 mx-auto max-w-3xl sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">Documenten Uploaden</h1>
                <p class="mt-2 text-neutral-600 dark:text-neutral-400">
                    WOO-verzoek: {{ $internalRequest->wooRequest->title }}
                </p>
            </div>

            @if(session('success'))
                <div class="p-4 mb-6 bg-green-50 rounded-lg dark:bg-green-900/20">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg dark:bg-neutral-800">
                {{-- Info Section --}}
                <div class="p-6 border-b border-neutral-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">Verzoek Details</h2>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-neutral-600 dark:text-neutral-400">Van:</span>
                            <span class="text-sm text-neutral-900 dark:text-white">{{ $internalRequest->caseManager->name }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-neutral-600 dark:text-neutral-400">Aan:</span>
                            <span class="text-sm text-neutral-900 dark:text-white">{{ $internalRequest->colleague_name ?? $internalRequest->colleague_email }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-neutral-600 dark:text-neutral-400">Beschrijving:</span>
                            <span class="text-sm text-neutral-900 dark:text-white">{{ $internalRequest->description }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="w-32 text-sm font-medium text-neutral-600 dark:text-neutral-400">Vervalt op:</span>
                            <span class="text-sm text-neutral-900 dark:text-white">{{ $internalRequest->token_expires_at->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Upload Form --}}
                <form action="{{ route('upload.store', $internalRequest->upload_token) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <div class="space-y-6">
                        {{-- Name --}}
                        <div>
                            <label for="submitted_by_name" class="block text-sm font-medium text-neutral-900 dark:text-white">
                                Uw naam (optioneel)
                            </label>
                            <input type="text"
                                   name="submitted_by_name"
                                   id="submitted_by_name"
                                   value="{{ old('submitted_by_name', $internalRequest->colleague_name) }}"
                                   class="block px-4 py-2 mt-1 w-full rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">
                            @error('submitted_by_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="submission_notes" class="block text-sm font-medium text-neutral-900 dark:text-white">
                                Notities bij upload (optioneel)
                            </label>
                            <textarea name="submission_notes"
                                      id="submission_notes"
                                      rows="3"
                                      class="block px-4 py-2 mt-1 w-full rounded-lg border border-neutral-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white"
                                      placeholder="Eventuele opmerkingen over de geüploade documenten...">{{ old('submission_notes') }}</textarea>
                            @error('submission_notes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- File Upload --}}
                        <div>
                            <label for="documents" class="block text-sm font-medium text-neutral-900 dark:text-white">
                                Documenten <span class="text-red-600">*</span>
                            </label>
                            <div class="mt-1">
                                <div class="flex justify-center px-6 pt-5 pb-6 rounded-lg border-2 border-dashed border-neutral-300 dark:border-neutral-700 hover:border-blue-400">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto w-12 h-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-neutral-600 dark:text-neutral-400">
                                            <label for="documents" class="relative font-medium text-rijksblauw rounded-md cursor-pointer hover:text-blue-500">
                                                <span>Upload bestanden</span>
                                                <input id="documents" name="documents[]" type="file" multiple required class="sr-only">
                                            </label>
                                            <p class="pl-1">of sleep ze hier naartoe</p>
                                        </div>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                            PDF, Word, afbeeldingen tot {{ config('woo.max_upload_size_mb', 50) }}MB per bestand
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div id="file-list" class="mt-3 space-y-2"></div>
                            @error('documents')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('documents.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info --}}
                        <div class="p-4 bg-blue-50 rounded-lg dark:bg-blue-900/10">
                            <div class="flex">
                                <svg class="flex-shrink-0 w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3 text-sm text-blue-700 dark:text-blue-300">
                                    <p class="font-medium">Let op:</p>
                                    <ul class="mt-2 ml-4 space-y-1 list-disc">
                                        <li>U kunt meerdere bestanden tegelijk uploaden</li>
                                        <li>De documenten worden automatisch verwerkt</li>
                                        <li>U kunt meerdere keren uploaden via deze link</li>
                                        <li>De case manager ontvangt een notificatie van uw upload</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end pt-4 border-t border-neutral-200 dark:border-neutral-700">
                            <button type="submit"
                                    class="px-6 py-2 text-sm font-semibold text-white bg-rijksblauw rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Documenten uploaden
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Previous Submissions --}}
                @if($internalRequest->submissions->count() > 0)
                <div class="p-6 border-t border-neutral-200 dark:border-neutral-700">
                    <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Eerdere uploads</h3>
                    <div class="mt-3 space-y-2">
                        @foreach($internalRequest->submissions as $submission)
                            <div class="flex justify-between items-center p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                                <div>
                                    <p class="text-sm font-medium text-neutral-900 dark:text-white">
                                        {{ $submission->documents_count }} document(en)
                                    </p>
                                    <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                        {{ $submission->created_at->format('d-m-Y H:i') }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                    Verzonden
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Show selected files
        document.getElementById('documents').addEventListener('change', function(e) {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';

            Array.from(e.target.files).forEach(file => {
                const div = document.createElement('div');
                div.className = 'flex items-center p-2 bg-neutral-50 dark:bg-neutral-900 rounded-lg';
                div.innerHTML = `
                    <svg class="mr-2 w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm text-neutral-900 dark:text-white">${file.name}</span>
                    <span class="ml-auto text-xs text-neutral-600 dark:text-neutral-400">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                `;
                fileList.appendChild(div);
            });
        });
    </script>
</body>
</html>

