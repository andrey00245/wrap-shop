<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\TurboSms\TurboSmsChannel;
use Illuminate\Notifications\Messages\SmsMessage;
use NotificationChannels\TurboSms\TurboSmsMessage;

class SendSmsNotification extends Notification
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [TurboSmsChannel::class];
    }

    public function toTurboSms($notifiable)
    {
        return (new TurboSmsMessage())->content($this->message)->test(false);
    }

    public function shouldQueue()
    {
        return false;
    }
}
