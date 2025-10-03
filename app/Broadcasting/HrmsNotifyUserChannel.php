<?php

namespace App\Broadcasting;

use App\Services\HrmsServices;
use Illuminate\Notifications\Notification;

class HrmsNotifyUserChannel
{
    public function send($notifiable, Notification $notification): void
    {
        $userId = $notifiable->id;
        $notif = $notification->toArray($notifiable);
        HrmsServices::setNotification($notification->getToken(), $userId, $notif);
    }
}
