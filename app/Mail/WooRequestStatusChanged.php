<?php

namespace App\Mail;

use App\Models\WooRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WooRequestStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public WooRequest $wooRequest,
        public string $oldStatus,
        public string $newStatus
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status wijziging voor uw WOO Verzoek',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.status-changed',
            with: [
                'wooRequest' => $this->wooRequest,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'statusLabel' => config('woo.woo_request_statuses')[$this->newStatus] ?? $this->newStatus,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
