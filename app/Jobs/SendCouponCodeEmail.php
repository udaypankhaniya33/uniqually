<?php

namespace App\Jobs;

use App\Mail\CouponCodeMailable;
use App\Subscriber;
use Carbon\Carbon;
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
    private $subscriberId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $coupon, $discount, $subscriberId)
    {
        $this->email = $email;
        $this->coupon = $coupon;
        $this->discount = $discount;
        $this->subscriberId = $subscriberId;
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
        if (!Mail::failures()) {
            Subscriber::where('id', $this->subscriberId)->update([
                'is_code_sent' => true,
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
