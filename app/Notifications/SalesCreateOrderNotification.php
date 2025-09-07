<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class SalesCreateOrderNotification extends Notification
{
   use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $order;
    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage(
            notification: new FcmNotification(
                title: 'Your request has been accepted',
                body: 'The request has been accepted and is being processed 😊' . $this->order->projectName
            )
        ))->custom([
                    'android' => [
                       'notification' => ['color' => '#FFD700'],
                        'fcm_options' => ['analytics_label' => 'order_created'],
                    ],
                    'apns' => [
                        'fcm_options' => ['analytics_label' => 'order_created'],
                    ],
                ]);
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
        ];
    }
}
