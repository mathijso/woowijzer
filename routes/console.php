<?php

use App\Jobs\RetryFailedDocumentProcessing;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule retry of failed document processing
$retryInterval = config('woo.woo_insight_api.retry_interval_minutes', 10);
Schedule::job(new RetryFailedDocumentProcessing)
    ->cron("*/{$retryInterval} * * * *")
    ->withoutOverlapping();
