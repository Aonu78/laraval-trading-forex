<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DirectUserNotification extends Notification
{
    use Queueable;

    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->payload['title'],
            'message' => $this->payload['message'],
            'url' => $this->payload['url'] ?? route('user.notifications'),
            'sent_by' => $this->payload['sent_by'] ?? 'admin',
        ];
    }
}
