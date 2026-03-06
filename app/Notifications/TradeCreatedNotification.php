<?php

namespace App\Notifications;

use App\Helpers\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TradeCreatedNotification extends Notification
{
    use Queueable;

    public $trade;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($trade)
    {
        $this->trade = $trade;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'user_id' => $this->trade->user_id,
            'message' => $this->trade->user->username . ' opened a ' . strtoupper($this->trade->trade_type) . ' trade on ' . $this->trade->currency . ' with amount ' . Helper::formatter($this->trade->trade_amount ?? 0),
            'url' => route('admin.trade', $this->trade->user_id),
        ];
    }
}
