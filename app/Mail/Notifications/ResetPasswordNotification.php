<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
    public function toMail($notifiable)
    {
        // dd($notifiable);
        $settings = App\Settings::all()->first();
        return (new MailMessage)
                    ->from($address = $settings->from_email, $name =  $settings->site_title)
                    ->subject('Password Reset')
                    ->line('Hello, '.$notifiable->name."!")
                    ->line('It seems that you have descided to reset your password.')
                    ->line('Press the button (or the link) below to reset, or just ignore this mail if you did not intend to reset your password.')
                    ->action('Reset Password', url('/password/reset/'.$this->token."?email=".$notifiable->email))
                    ->line('Thanks.');
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
