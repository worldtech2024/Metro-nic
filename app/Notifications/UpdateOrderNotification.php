<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class UpdateOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $order;
    protected $type; 
    public function __construct($order, $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage(
            notification: new FcmNotification(
                title: 'تم تعيينك للعمل',
                body: 'تم تعيينك كموظف ' . ($this->type == 'buy' ? 'شراء' : 'تركيب') . ' للطلب رقم #' . $this->order->projectName
            )
        ))->custom([
                    'android' => [
                        'notification' => ['color' => '#0A0A0A'],
                        'fcm_options' => ['analytics_label' => 'order_update'],
                    ],
                    'apns' => [
                        'fcm_options' => ['analytics_label' => 'order_update'],
                    ],
                ]);
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'type' => $this->type,
        ];
    }
}
