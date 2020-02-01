<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderId;
    private $orderDate;
    private $orderItems;
    private $sendTo;
    private $netValue;
    private $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId, $orderDate, $orderItems, $sendTo, $netValue, $name)
    {
        $this->orderId = $orderId;
        $this->orderDate = $orderDate;
        $this->orderItems = $orderItems;
        $this->sendTo = $sendTo;
        $this->netValue = $netValue;
        $this->name = decrypt($name);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailable = new OrderConfirmationMailable($this->orderId, $this->orderDate, $this->orderItems, $this->netValue, $this->name);
        Mail::to($this->sendTo)->cc(env('ADMIN_EMAIL'))->send($mailable);
    }
}
