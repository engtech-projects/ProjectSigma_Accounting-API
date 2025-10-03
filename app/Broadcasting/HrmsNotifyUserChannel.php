<?php

namespace App\Broadcasting;

use App\Services\HrmsServices;

class HrmsNotifyUserChannel
{
    public function send($notifiable, $notification): void
    {
        $userId = $notifiable->id;
        $notif = $notification->toArray($notifiable);
        HrmsServices::setNotification($notification->getToken(), $userId, $notif);
    }
}
