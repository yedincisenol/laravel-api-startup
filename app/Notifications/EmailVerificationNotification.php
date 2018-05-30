<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(trans('user.verification_code_title', ['name' => config('api.name')]))
            ->view('mail.email_verification', [
                'user' => $notifiable,
                'name' => config('api.name'),
                'link' => config('project.web_base_url').
                    'email-verify?q='.base64_encode($notifiable->email.'|'.$notifiable->verification_code),
            ]);
    }

    /**
     * Notification text.
     *
     * @param $notifiable
     *
     * @return mixed
     */
    public function text($notifiable)
    {
    }

    /**
     * Notification lik(uri).
     *
     * @return mixed
     */
    public function uri()
    {
    }

    /**
     * Notification right image url.
     *
     * @return mixed
     */
    public function rightImageUrl()
    {
    }

    /**
     * Notification left image url.
     *
     * @return mixed
     */
    public function leftImageUrl()
    {
    }

    /**
     * Notification title.
     *
     * @param $notifiable
     *
     * @return mixed
     */
    public function title($notifiable)
    {
    }

    /**
     * Notification note.
     *
     * @param $notifiable
     *
     * @return mixed
     */
    public function note($notifiable)
    {
    }

    /**
     * Notification type.
     *
     * @return mixed
     */
    public function type()
    {
    }

    /**
     * Notification point.
     *
     * @return mixed
     */
    public function point()
    {
    }

    /**
     * Notification releated user (Not owner).
     *
     * @return mixed
     */
    public function user()
    {
    }
}
