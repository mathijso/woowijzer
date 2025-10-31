<?php

namespace App\Jobs;

use App\Models\InternalRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ExpireOldTokens implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Expiring old upload tokens');

            // Find tokens that are expired but not marked as such
            $expiredRequests = InternalRequest::query()
                ->whereIn('status', ['pending', 'submitted'])
                ->where('token_expires_at', '<=', now())
                ->get();

            $expiredCount = 0;

            foreach ($expiredRequests as $request) {
                $request->update([
                    'status' => 'expired',
                    'closed_at' => now(),
                ]);
                $expiredCount++;
            }

            Log::info('Old upload tokens expired', [
                'expired_count' => $expiredCount,
            ]);

            // Send reminders for tokens expiring soon
            $this->sendExpiryReminders();
        } catch (\Exception $e) {
            Log::error('Failed to expire old tokens', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send reminders for tokens expiring soon
     */
    protected function sendExpiryReminders(): void
    {
        $reminderDays = config('woo.upload_token_reminder_days', 3);

        $expiringSoon = InternalRequest::query()
            ->whereIn('status', ['pending', 'submitted'])
            ->whereBetween('token_expires_at', [
                now(),
                now()->addDays($reminderDays),
            ])
            ->get();

        foreach ($expiringSoon as $request) {
            // TODO: Send email reminder
            // Mail::to($request->colleague_email)->send(new UploadTokenExpiring($request));

            Log::info('Expiry reminder should be sent', [
                'internal_request_id' => $request->id,
                'colleague_email' => $request->colleague_email,
                'expires_at' => $request->token_expires_at,
            ]);
        }
    }
}
