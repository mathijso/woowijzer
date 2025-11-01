<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Link Verlopen - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 dark:bg-neutral-900">
    <div class="flex items-center justify-center min-h-screen py-12">
        <div class="mx-auto max-w-md px-4">
            <div class="p-8 text-center bg-white rounded-xl shadow-lg dark:bg-neutral-800">
                <div class="mx-auto flex items-center justify-center w-16 h-16 bg-red-100 rounded-full dark:bg-red-900/20">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h1 class="mt-6 text-2xl font-bold text-neutral-900 dark:text-white">
                    Upload Link Verlopen
                </h1>

                <p class="mt-4 text-neutral-600 dark:text-neutral-400">
                    Deze upload link is helaas verlopen op <strong>{{ $internalRequest->token_expires_at->format('d F Y') }}</strong>.
                </p>

                <div class="mt-6 p-4 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">
                        <strong>WOO-verzoek:</strong><br>
                        {{ $internalRequest->wooRequest->title }}
                    </p>
                </div>

                <div class="mt-8 space-y-3 text-sm text-neutral-600 dark:text-neutral-400">
                    <p>
                        Als u nog documenten wilt aanleveren, neem dan contact op met:
                    </p>
                    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/10">
                        <p class="font-medium text-blue-900 dark:text-blue-200">
                            {{ $internalRequest->caseManager->name }}
                        </p>
                        <p class="text-blue-700 dark:text-blue-300">
                            {{ $internalRequest->caseManager->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


