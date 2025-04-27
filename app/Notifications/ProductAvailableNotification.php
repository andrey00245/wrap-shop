<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProductAvailableNotification extends Notification
{
    use Queueable;

    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Товар "' . $this->product->name . '" знову в наявності!')
            ->greeting('Привіт, ' . $notifiable->name . '!')
            ->line('Товар "' . $this->product->name . '" тепер є на складі.')
            ->action('Переглянути товар', route('products.show', $this->product->slugEn))
            ->line('Дякуємо, що обрали нас!');
    }
}
