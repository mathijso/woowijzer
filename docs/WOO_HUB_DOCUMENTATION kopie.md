# WOO Hub - Backend Documentatie

## ✅ Wat is er gebouwd?

Het complete backend systeem voor de WOO Hub is geïmplementeerd met:

-   Database schema met 7 tabellen + migrations
-   Eloquent models met relaties en business logic
-   Controllers voor alle CRUD operaties
-   Services voor document processing en AI integratie
-   Background jobs voor async processing
-   Authorization policies en middleware
-   Email notificaties
-   API webhook ondersteuning
-   Basis Blade views voor navigatie

## 🎯 Architectuur

### Data Flow

```
Burger uploads PDF → WooRequest → ProcessWooRequestDocument Job
                                          ↓
                        Questions worden geëxtraheerd
                                          ↓
Case Manager wijst toe → InternalRequest → Email naar collega
                                          ↓
Collega upload via token → Submission → Documents
                                          ↓
                        ProcessUploadedDocument Job
                                          ↓
                        Auto-linking aan Questions
```

### Database Schema

**Users** (burgers, case_managers, colleagues)
└─ **WooRequests**
├─ **Questions** (geëxtraheerd uit WOO verzoek)
├─ **InternalRequests** (naar collega's)
│ └─ **Submissions** (upload sessies)
│ └─ **Documents**
└─ **DocumentQuestionLinks** (many-to-many koppeling)

## 🔐 Rollen & Permissies

### Burger (`role = 'burger'`)

-   Kan WOO verzoeken indienen
-   Ziet alleen eigen verzoeken
-   Kan status volgen

### Case Manager (`role = 'case_manager'`)

-   Ziet alle WOO verzoeken
-   Kan verzoeken toewijzen
-   Kan interne verzoeken versturen
-   Kan documenten koppelen aan vragen
-   Kan status wijzigen

### Colleague (`role = 'colleague'`)

-   Ontvangt upload links via email
-   Kan documenten uploaden via publiek portal (geen login!)

## 📍 Routes

### Public Routes (geen auth)

-   `GET/POST /upload/{token}` - Upload portal voor collega's

### Burger Routes (`auth` + `burger`)

-   `GET /woo-requests` - Lijst eigen verzoeken
-   `GET /woo-requests/{id}` - Detail view
-   `POST /woo-requests` - Nieuw verzoek indienen

### Case Manager Routes (`auth` + `case_manager`)

-   `GET /cases` - Alle cases overzicht
-   `GET /cases/{id}` - Case detail met vragen/documenten
-   `POST /internal-requests` - Nieuw intern verzoek
-   `POST /cases/{id}/auto-link-documents` - Auto-link functie
-   `POST /cases/{id}/generate-summaries` - AI samenvattingen

### API Routes (webhook)

-   `POST /api/webhook/processing` - Voor API callbacks

## 🗂️ Belangrijke Bestanden

### Models

-   `app/Models/User.php` - User met role helpers
-   `app/Models/WooRequest.php` - Kern model met progress tracking
-   `app/Models/Question.php` - Vragen uit WOO verzoek
-   `app/Models/InternalRequest.php` - Upload verzoeken
-   `app/Models/Submission.php` - Upload sessies (de extra laag!)
-   `app/Models/Document.php` - Geüploade bestanden
-   `app/Models/DocumentQuestionLink.php` - Pivot model

### Services

-   `app/Services/DocumentProcessingService.php` - API integratie
-   `app/Services/QuestionExtractionService.php` - Vraag extractie
-   `app/Services/DocumentLinkingService.php` - Auto-linking logic

### Jobs

-   `app/Jobs/ProcessWooRequestDocument.php` - WOO PDF verwerken
-   `app/Jobs/ProcessUploadedDocument.php` - Upload verwerken
-   `app/Jobs/GenerateQuestionSummaries.php` - AI samenvattingen
-   `app/Jobs/ExpireOldTokens.php` - Token cleanup (scheduled)

### Controllers

-   `WooRequestController` - CRUD voor burgers
-   `CaseOverviewController` - Dashboard case managers
-   `InternalRequestController` - Intern verzoek management
-   `UploadPortalController` - Publiek upload portal
-   `DocumentController` - Document management
-   `QuestionController` - Vraag management
-   `ApiWebhookController` - API callbacks

## 🎨 Voor de Front-end Developer

### Huidige Status

✅ Alle Blade views zijn gemaakt met Tailwind CSS
✅ Basis navigatie werkt (dashboard → index → detail)
✅ Dark mode ondersteuning
✅ Responsive design
✅ Rijksoverheid font integratie

### Vue3 Integratie Opties

#### Optie 1: Inertia.js (Aanbevolen)

```bash
composer require inertiajs/inertia-laravel
npm install @inertiajs/vue3
```

Voordelen:

-   Behoud Laravel routing en controllers
-   Vue componenten als "views"
-   Server-side routing
-   Geen API layer nodig

#### Optie 2: Laravel API + Vue SPA

-   Maak API endpoints in controllers
-   Vue app als volledig gescheiden SPA
-   Sanctum voor auth

#### Optie 3: Hybrid (Huidige Blade + Vue Components)

-   Behoud Blade layouts
-   Vue voor interactieve componenten
-   `@vite` directive voor hot reload

### Belangrijke Features voor Front-end

#### 1. **Documenten Uploaden**

```javascript
// Multi-file upload met progress
const uploadDocuments = async (files, token) => {
    const formData = new FormData();
    files.forEach((file) => formData.append("documents[]", file));

    await axios.post(`/upload/${token}`, formData, {
        onUploadProgress: (e) => {
            // Progress bar update
        },
    });
};
```

#### 2. **Real-time Progress Tracking**

-   Job status polling of WebSocket
-   Progress percentage updates
-   Document processing status

#### 3. **Document-Question Linking**

```javascript
// Drag & drop interface
const linkDocument = async (documentId, questionId) => {
    await axios.post(`/documents/${documentId}/link-to-question`, {
        question_id: questionId,
        confirmed: false,
    });
};
```

#### 4. **Rich Text Editor**

Voor vraag summaries en notities:

-   TipTap of Quill.js
-   Markdown support
-   Syntax highlighting

#### 5. **File Preview**

-   PDF.js voor PDF preview
-   Office docs preview
-   Image viewer

### API Endpoints (als je SPA kiest)

Alle controllers hebben al JSON response support via:

```php
if ($request->wantsJson()) {
    return response()->json($data);
}
```

Je kunt API routes toevoegen in `routes/api.php`:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('woo-requests', WooRequestController::class);
    Route::apiResource('cases', CaseOverviewController::class);
});
```

## ⚙️ Configuratie & Setup

### 1. Environment Variables

```env
# API Integratie (van je collega)
DOCUMENT_PROCESSING_API_URL=https://api.example.com
DOCUMENT_PROCESSING_API_KEY=your-api-key

# Upload Settings
UPLOAD_TOKEN_EXPIRY_DAYS=28
MAX_UPLOAD_SIZE_MB=50

# Queue (verplicht voor background jobs!)
QUEUE_CONNECTION=database
```

### 2. Queue Worker Starten

```bash
php artisan queue:work --tries=3
```

### 3. Scheduled Tasks

In `app/Console/Kernel.php` toevoegen:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->job(new ExpireOldTokens)->daily();
}
```

En cron:

```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Database Seeden

```bash
php artisan db:seed --class=WooHubSeeder
```

## 🧪 Test Accounts

```
Burger:
  Email: burger@example.com
  Password: password

Case Manager:
  Email: casemanager@example.com
  Password: password

Colleague:
  Email: colleague@example.com
  Password: password
```

## 📊 Belangrijke Queries

### Progress Berekening

```php
$progressPercentage = $wooRequest->progress_percentage; // Accessor in model
```

### Onbeantwoorde Vragen

```php
$unanswered = Question::unanswered()->get();
```

### Actieve Upload Tokens

```php
$active = InternalRequest::active()->get();
```

### Documents zonder Processing

```php
$unprocessed = Document::unprocessed()->get();
```

## 🚀 Deployment Checklist

-   [ ] `.env` configureren (API keys, queue)
-   [ ] `php artisan migrate`
-   [ ] `php artisan storage:link`
-   [ ] Queue worker als supervisor/systemd service
-   [ ] Cron job voor scheduled tasks
-   [ ] Email server configureren (Mailtrap/SES/etc)
-   [ ] `npm run build` voor productie assets

## 📝 Todo's voor Productie

1. **Emails daadwerkelijk versturen**

    - Update `InternalRequestController@store`
    - Update `Jobs/ExpireOldTokens.php`
    - Uncomment Mail::send() calls

2. **File Type Validatie**

    - Configured in `config/woo.php`
    - Update based on requirements

3. **Rate Limiting**

    - Add to upload routes
    - Prevent abuse

4. **Audit Logging**

    - Log belangrijke acties
    - Case manager actions
    - Document access

5. **Backups**
    - Database backups
    - Document storage backups

## 🐛 Debugging

### Queue Jobs Falen

```bash
php artisan queue:failed
php artisan queue:retry {id}
```

### Log Files

```bash
tail -f storage/logs/laravel.log
```

### Test Document Processing

```php
use App\Services\DocumentProcessingService;

$service = app(DocumentProcessingService::class);
$available = $service->isAvailable(); // Check API status
```

## 💡 Tips

1. **Livewire Components** zijn al beschikbaar - gebruik voor interactieve features
2. **Alpine.js** is beschikbaar voor kleine interacties
3. **Dark mode** is al geïmplementeerd - behoud consistency
4. **Rijksoverheid fonts** zijn geladen - gebruik juiste font classes

## 🎯 Volgende Stappen

1. Kies front-end approach (Inertia/SPA/Hybrid)
2. Implementeer file upload UI met drag & drop
3. Bouw document viewer met preview
4. Maak document-question linking interface
5. Add real-time notifications
6. Implementeer search & filters
7. Add export functionality (PDF reports)

## 📞 Vragen?

De backend is volledig functioneel en gedocumenteerd. Alle controllers returnen data die ready is voor JSON responses. Policies zorgen voor authorization. Jobs draaien async in de queue.

Succes met de front-end! 🚀
