<?php

namespace App\Mail;

use App\Models\InternalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InternalRequestSent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public InternalRequest $internalRequest
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verzoek om documenten - WOO Verzoek',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.internal-request',
            with: [
                'internalRequest' => $this->internalRequest,
                'wooRequest' => $this->internalRequest->wooRequest,
                'caseManager' => $this->internalRequest->caseManager,
                'uploadUrl' => route('upload.show', $this->internalRequest->upload_token),
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
