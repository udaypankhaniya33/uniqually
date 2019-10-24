<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponCodeMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $coupon;
    public $discount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($coupon, $discount)
    {
        $this->coupon = $coupon;
        $this->discount = $discount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('vManageTax - Discount Code')->view('emails.coupon_code');
    }
}
