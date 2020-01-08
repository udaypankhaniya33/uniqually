<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $ip;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $ip)
    {
        $this->name = $name;
        $this->ip = $ip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('UniqAlly password has been changed')->view('emails.password_changed');
    }
}
