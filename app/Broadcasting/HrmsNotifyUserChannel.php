<?php

namespace App\Broadcasting;

use App\Services\ApiServices\HrmsService;
use Illuminate\Notifications\Notification;

class HrmsNotifyUserChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $userId = $notifiable->id;
        $notif = $notification->toArray($notifiable);
        HrmsService::setNotification($notification->getToken(), $userId, $notif);
    }
}
