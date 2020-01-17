<?php

namespace App\Jobs;

use App\Mail\OnboardingMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOnboardingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $title;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $title)
    {
        $this->user = $user;
        $this->title = $title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailable = new OnboardingMailable(decrypt($this->user->name), $this->title);
        Mail::to($this->user->email)->send($mailable);
    }
}
