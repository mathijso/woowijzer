<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Document Processing API
    |--------------------------------------------------------------------------
    |
    | Configuration for the custom document processing API that extracts
    | questions and content from uploaded WOO documents.
    |
    */

    'document_processing' => [
        'api_url' => env('DOCUMENT_PROCESSING_API_URL'),
        'api_key' => env('DOCUMENT_PROCESSING_API_KEY'),
        'timeout' => env('DOCUMENT_PROCESSING_TIMEOUT', 120), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Token Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for upload tokens used by colleagues to submit documents.
    |
    */

    'upload_token_expiry_days' => env('UPLOAD_TOKEN_EXPIRY_DAYS', 28),
    'upload_token_reminder_days' => env('UPLOAD_TOKEN_REMINDER_DAYS', 3),

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for file uploads and storage.
    |
    */

    'max_upload_size_mb' => env('MAX_UPLOAD_SIZE_MB', 50),
    'allowed_file_types' => [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'txt' => 'text/plain',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Status Configuration
    |--------------------------------------------------------------------------
    |
    | Available statuses for various entities in the system.
    |
    */

    'woo_request_statuses' => [
        'submitted' => 'Ingediend',
        'in_review' => 'In beoordeling',
        'in_progress' => 'In behandeling',
        'completed' => 'Afgerond',
        'rejected' => 'Afgewezen',
    ],

    'question_statuses' => [
        'unanswered' => 'Onbeantwoord',
        'partially_answered' => 'Gedeeltelijk beantwoord',
        'answered' => 'Beantwoord',
    ],

    'internal_request_statuses' => [
        'pending' => 'In afwachting',
        'submitted' => 'Ingediend',
        'completed' => 'Afgerond',
        'expired' => 'Verlopen',
    ],

];
