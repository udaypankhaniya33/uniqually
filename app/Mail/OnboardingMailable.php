<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OnboardingMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $title)
    {
        $this->username = $username;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->title === 'Bookkeeping'){
            return $this->subject('Your Uniqally On-boarding experience begins now!')->view('emails.onboarding_bk');
        }else{
            return $this->subject('Your Uniqally On-boarding experience begins now!')->view('emails.onboarding');
        }

    }
}
