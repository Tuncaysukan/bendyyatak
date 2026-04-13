<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(ContactMessage $msg)
    {
        $this->message = $msg;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yeni İletişim Mesajı: ' . $this->message->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_message',
        );
    }
}
