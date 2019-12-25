<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorAuthenticationForLogin extends Notification
{
    use Queueable;

    private $user;
    private $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
                    ->subject('Two factor authentication code for uniqally.com')
                    ->line('Welcome back, '.$this->user->name.'. Please use bellow authentication code to verify your login.')
                    ->line('Authentication Code: '. $this->code)
                    ->line('Thank you for using uniqally!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
