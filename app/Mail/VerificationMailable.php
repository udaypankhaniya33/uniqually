<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $activationCode;
    public $email;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($activationCode, $email, $name)
    {
        $this->activationCode = $activationCode;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirm your email address for uniqally.com')->view('emails.verification');
    }
}
