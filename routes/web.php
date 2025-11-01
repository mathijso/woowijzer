<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', function () {
    return redirect()->route('welcome');
})->name('home');

// woohub Routes
Route::get('/over', function () {
    return view('about');
})->name('about');

Route::get('/document-samenvatten', function () {
    return view('document-summarize');
})->name('document.summarize');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Public upload portal (NO authentication required)
Route::get('/upload/{token}', [App\Http\Controllers\UploadPortalController::class, 'show'])
    ->name('upload.show');
Route::post('/upload/{token}', [App\Http\Controllers\UploadPortalController::class, 'store'])
    ->name('upload.store');

// API webhook endpoint
Route::post('/api/webhook/processing', [App\Http\Controllers\ApiWebhookController::class, 'receiveProcessing'])
    ->name('api.webhook.processing');

// Authenticated routes - WOO Request Management
Route::middleware(['auth'])->group(function () {
    // WOO Requests - for burgers
    Route::resource('woo-requests', App\Http\Controllers\WooRequestController::class)->except(['show']);
    Route::get('woo-requests/{wooRequest}/{tab}', [App\Http\Controllers\WooRequestController::class, 'show'])
        ->where('tab', 'questions|decision|timeline|documents|internal-requests')
        ->name('woo-requests.show.tab');
    Route::get('woo-requests/{wooRequest}', [App\Http\Controllers\WooRequestController::class, 'show'])
        ->name('woo-requests.show');
    Route::get('woo-requests-manual/create', [App\Http\Controllers\WooRequestController::class, 'createManual'])
        ->name('woo-requests.create-manual');
    Route::post('woo-requests-manual/store', [App\Http\Controllers\WooRequestController::class, 'storeManual'])
        ->name('woo-requests.store-manual');
    Route::post('woo-requests/{wooRequest}/assign-case-manager', [App\Http\Controllers\WooRequestController::class, 'assignCaseManager'])
        ->name('woo-requests.assign-case-manager');
    Route::post('woo-requests/{wooRequest}/pickup', [App\Http\Controllers\WooRequestController::class, 'pickupCase'])
        ->name('woo-requests.pickup');

    // Case manager actions on WOO requests
    Route::post('woo-requests/{wooRequest}/update-status', [App\Http\Controllers\WooRequestController::class, 'updateStatus'])
        ->name('woo-requests.update-status');
    Route::post('woo-requests/{wooRequest}/auto-link-documents', [App\Http\Controllers\WooRequestController::class, 'autoLinkDocuments'])
        ->name('woo-requests.auto-link-documents');
    Route::post('woo-requests/{wooRequest}/generate-summaries', [App\Http\Controllers\WooRequestController::class, 'generateSummaries'])
        ->name('woo-requests.generate-summaries');
    Route::get('woo-requests/{wooRequest}/generate-report', [App\Http\Controllers\WooRequestController::class, 'generateReport'])
        ->name('woo-requests.generate-report');
    Route::post('woo-requests/{wooRequest}/retry-processing', [App\Http\Controllers\WooRequestController::class, 'retryProcessing'])
        ->name('woo-requests.retry-processing');
    Route::get('woo-requests/{wooRequest}/download-document', [App\Http\Controllers\WooRequestController::class, 'downloadDocument'])
        ->name('woo-requests.download-document');

    // Case-scoped Documents
    Route::get('documents', [App\Http\Controllers\DocumentController::class, 'index'])
        ->name('documents.index');
    Route::get('case/{wooRequest}/documents', function (App\Models\WooRequest $wooRequest) {
        return redirect()->route('woo-requests.show.tab', [$wooRequest, 'documents'], 301);
    })->name('cases.documents.index');
    Route::get('case/{wooRequest}/documents/{document}/{tab}', [App\Http\Controllers\DocumentController::class, 'show'])
        ->where('tab', 'overview|summary|timeline|content|questions')
        ->name('cases.documents.show.tab');
    Route::get('case/{wooRequest}/documents/{document}', [App\Http\Controllers\DocumentController::class, 'show'])
        ->name('cases.documents.show');
    Route::delete('case/{wooRequest}/documents/{document}', [App\Http\Controllers\DocumentController::class, 'destroy'])
        ->name('cases.documents.destroy');
    Route::get('case/{wooRequest}/documents/{document}/download', [App\Http\Controllers\DocumentController::class, 'download'])
        ->name('cases.documents.download');

    // Document-Question linking (case-scoped)
    Route::post('case/{wooRequest}/documents/{document}/link-to-question', [App\Http\Controllers\DocumentController::class, 'linkToQuestion'])
        ->name('cases.documents.link-to-question');
    Route::delete('case/{wooRequest}/documents/{document}/unlink-from-question/{question}', [App\Http\Controllers\DocumentController::class, 'unlinkFromQuestion'])
        ->name('cases.documents.unlink-from-question');
    Route::post('case/{wooRequest}/documents/{document}/confirm-link/{question}', [App\Http\Controllers\DocumentController::class, 'confirmLink'])
        ->name('cases.documents.confirm-link');

    // Case-scoped Questions
    Route::get('questions', [App\Http\Controllers\QuestionController::class, 'index'])
        ->name('questions.index');
    Route::get('case/{wooRequest}/questions/{question}', [App\Http\Controllers\QuestionController::class, 'show'])
        ->name('cases.questions.show');
    Route::put('case/{wooRequest}/questions/{question}', [App\Http\Controllers\QuestionController::class, 'update'])
        ->name('cases.questions.update');
    Route::post('case/{wooRequest}/questions/{question}/generate-summary', [App\Http\Controllers\QuestionController::class, 'generateSummary'])
        ->name('cases.questions.generate-summary');
    Route::delete('case/{wooRequest}/questions/{question}', [App\Http\Controllers\QuestionController::class, 'destroy'])
        ->name('cases.questions.destroy');

    // Backwards compatibility: Redirect old ID-based routes to UUID routes
    Route::get('documents/{id}', function ($id) {
        if (is_numeric($id)) {
            $document = App\Models\Document::find($id);
            if ($document && $document->wooRequest) {
                return redirect()->route('cases.documents.show', [$document->wooRequest, $document], 301);
            }
        }
        abort(404);
    })->where('id', '[0-9]+')->name('documents.show.legacy');

    Route::get('woo-requests/{id}', function ($id) {
        if (is_numeric($id)) {
            $wooRequest = App\Models\WooRequest::find($id);
            if ($wooRequest) {
                return redirect()->route('woo-requests.show', [$wooRequest, 'questions'], 301);
            }
        }
        abort(404);
    })->where('id', '[0-9]+')->name('woo-requests.show.legacy');

    Route::get('questions/{id}', function ($id) {
        if (is_numeric($id)) {
            $question = App\Models\Question::find($id);
            if ($question && $question->wooRequest) {
                return redirect()->route('cases.questions.update', [$question->wooRequest, $question], 301);
            }
        }
        abort(404);
    })->where('id', '[0-9]+')->name('questions.show.legacy');

    Route::get('internal-requests/{id}', function ($id) {
        if (is_numeric($id)) {
            $internalRequest = App\Models\InternalRequest::find($id);
            if ($internalRequest) {
                return redirect()->route('internal-requests.show', $internalRequest, 301);
            }
        }
        abort(404);
    })->where('id', '[0-9]+')->name('internal-requests.show.legacy');

    Route::get('cases/{id}', function ($id) {
        if (is_numeric($id)) {
            $wooRequest = App\Models\WooRequest::find($id);
            if ($wooRequest) {
                return redirect()->route('woo-requests.show', [$wooRequest, 'questions'], 301);
            }
        }
        abort(404);
    })->where('id', '[0-9]+')->name('cases.show.legacy');
});

// Case Manager routes
Route::middleware(['auth', App\Http\Middleware\EnsureCaseManager::class])->group(function () {
    // Case management dashboard
    Route::get('cases', [App\Http\Controllers\CaseOverviewController::class, 'index'])
        ->name('cases.index');

    // Backwards compatibility - redirect cases.show to woo-requests.show
    Route::get('cases/{wooRequest}', function (App\Models\WooRequest $wooRequest) {
        return redirect()->route('woo-requests.show', [$wooRequest, 'questions'], 301);
    })->name('cases.show');

    // Backwards compatibility - route aliases for case actions
    Route::post('cases/{wooRequest}/auto-link-documents', [App\Http\Controllers\WooRequestController::class, 'autoLinkDocuments'])
        ->name('cases.auto-link-documents');
    Route::post('cases/{wooRequest}/generate-summaries', [App\Http\Controllers\WooRequestController::class, 'generateSummaries'])
        ->name('cases.generate-summaries');
    Route::post('cases/{wooRequest}/update-status', [App\Http\Controllers\WooRequestController::class, 'updateStatus'])
        ->name('cases.update-status');
    Route::get('cases/{wooRequest}/generate-report', [App\Http\Controllers\WooRequestController::class, 'generateReport'])
        ->name('cases.generate-report');

    // Case assignment actions
    Route::post('cases/{wooRequest}/pickup', [App\Http\Controllers\CaseOverviewController::class, 'pickupCase'])
        ->name('cases.pickup');
    Route::post('cases/{wooRequest}/assign', [App\Http\Controllers\CaseOverviewController::class, 'assignCase'])
        ->name('cases.assign');

    // Internal requests
    Route::resource('internal-requests', App\Http\Controllers\InternalRequestController::class)
        ->except(['edit', 'update']);
    Route::post('internal-requests/{internalRequest}/resend', [App\Http\Controllers\InternalRequestController::class, 'resend'])
        ->name('internal-requests.resend');
    Route::post('internal-requests/{internalRequest}/expire', [App\Http\Controllers\InternalRequestController::class, 'expire'])
        ->name('internal-requests.expire');
    Route::post('internal-requests/{internalRequest}/complete', [App\Http\Controllers\InternalRequestController::class, 'complete'])
        ->name('internal-requests.complete');
});

// Mail preview routes (for demo purposes)
Route::prefix('mail')->name('preview.mail.')->group(function () {
    Route::get('document-uploaded/{submission}', function (App\Models\Submission $submission) {
        $submission->loadCount('documents');
        return new App\Mail\DocumentUploaded($submission);
    })->name('document-uploaded');

    Route::get('internal-request-sent/{internalRequest}', function (App\Models\InternalRequest $internalRequest) {
        return new App\Mail\InternalRequestSent($internalRequest);
    })->name('internal-request-sent');

    Route::get('upload-token-expiring/{internalRequest}', function (App\Models\InternalRequest $internalRequest) {
        return new App\Mail\UploadTokenExpiring($internalRequest);
    })->name('upload-token-expiring');

    Route::get('woo-request-status-changed/{wooRequest}/{old}/{new}', function (App\Models\WooRequest $wooRequest, string $old, string $new) {
        return new App\Mail\WooRequestStatusChanged($wooRequest, $old, $new);
    })->name('woo-request-status-changed');
});
