<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPasswordResetToken extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetToken;

    public function __construct($user, $resetToken)
    {
        $this->user = $user;
        $this->resetToken = $resetToken;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Request',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
            with: [
                'user' => $this->user,
                'resetToken' => $this->resetToken,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
