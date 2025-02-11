<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;

class PasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address('drshtdhiman29@gmail.com', 'Test Sender'),
            subject: 'Password same'
        );
    }

    public function build()
    {
        return $this->subject('Your Password Has Been Changed')
                    ->html("
                        <p>Hello {$this->user->name},</p>
                        <p>Your password has been same.</p>
                        <p>Thank you,</p>
                        <p>" . e(env('APP_NAME')) . "</p>
                    ");
    }
}