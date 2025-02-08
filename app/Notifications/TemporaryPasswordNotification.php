<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TemporaryPasswordNotification extends Notification
{
    public $email;
    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Доброго дня!')
            ->line('Ви успішно зареєстровані на нашому сайті.')
            ->line('Ваш логін: ' . $this->email)
            ->line('Ваш тимчасовий пароль: ' . $this->password)
            ->line('Будь ласка, використовуйте його для входу в систему та не забудьте змінити його після першого входу.')
            ->line('Дякуємо за реєстрацію!')
            ->action('Перейти до особистого кабінету', url('/account'));
    }
}
