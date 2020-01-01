<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $twoFactorCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($twoFactorCode)
    {
        $this->twoFactorCode = $twoFactorCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Two factor authentication code for uniqally.com')->view('emails.two_factor_code');
    }
}
