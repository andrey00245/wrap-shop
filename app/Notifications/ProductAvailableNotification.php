<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductAvailableNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Product $product,
        protected string $userName = '',
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $name = $this->userName !== '' ? $this->userName : 'друже';

        return (new MailMessage)
            ->subject('Товар "'.$this->product->name.'" знову в наявності!')
            ->greeting('Привіт, '.$name.'!')
            ->line('Товар "'.$this->product->name.'" тепер є на складі.')
            ->action('Переглянути товар', route('products.show', $this->product->slugEn))
            ->line('Дякуємо, що обрали нас!');
    }
}
