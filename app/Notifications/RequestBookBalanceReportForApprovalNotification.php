<?php

namespace App\Notifications;

use App\Broadcasting\HrmsNotifyUserChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestBookBalanceReportForApprovalNotification extends Notification
{
    use Queueable;
    private $token;
    private $model;
    public function __construct($token, $model)
    {
        $this->token = $token;
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [HrmsNotifyUserChannel::class];
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'A request for review of (Report Type) report requires your approval.',
            'module' => 'Accounting',
            'action' => 'View',
        ];
    }
}
