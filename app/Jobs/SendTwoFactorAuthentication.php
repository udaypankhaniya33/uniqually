<?php

namespace App\Jobs;

use App\Notifications\TwoFactorAuthenticationForLogin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTwoFactorAuthentication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->name = decrypt($this->user->name);
        $this->user->notify(new TwoFactorAuthenticationForLogin($this->user, $this->code));
    }
}
