<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $orderId;
    public $orderDate;
    public $orderItems;
    public $netValue;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderId, $orderDate, $orderItems, $netValue)
    {
        $this->orderId = $orderId;
        $this->orderDate = $orderDate;
        $this->orderItems = $orderItems;
        $this->netValue = $netValue;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('UniqAlly Order Confirmation - #'.$this->orderId)->view('emails.order_confirmation');
    }
}
