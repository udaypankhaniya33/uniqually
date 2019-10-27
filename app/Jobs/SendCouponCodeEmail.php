<?php

namespace App\Jobs;

use App\Mail\CouponCodeMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCouponCodeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $coupon;
    private $discount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $coupon, $discount)
    {
        $this->email = $email;
        $this->coupon = $coupon;
        $this->discount = $discount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new CouponCodeMailable($this->coupon, $this->discount);
        Mail::to($this->email)->send($email);
    }
}
