<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function __construct($token)
    {
        parent::__construct($token);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Скидання пароля')
            ->line('Ви отримали цей лист, бо отримано запит на скидання пароля для вашого акаунту.')
            ->action('Скинути пароль', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Посилання для скидання пароля дійсне 60 хвилин.')
            ->line('Якщо ви не робили цього запиту, ігноруйте цей лист.');
    }
}
