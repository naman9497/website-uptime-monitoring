<?php

namespace App\Mail;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class WebsiteDownAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Website $website
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('do-not-reply@example.com', 'Website Monitor'),
            subject: "{$this->website->url} is down!",
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.website-down-text',
            html: 'emails.website-down-html',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
