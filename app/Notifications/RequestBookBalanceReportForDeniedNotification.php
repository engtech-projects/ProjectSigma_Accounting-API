<?php

namespace App\Notifications;

use App\Broadcasting\HrmsNotifyUserChannel;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestBookBalanceReportForDeniedNotification extends Notification
{
    use Queueable;
    private $token;
    private $model;
    public function __construct($token, Voucher $model)
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
            'message' => 'The Report Request has been DENIED, it may lack or have incorrect information.',
            'module' => 'Accounting',
            'action' => 'View',
        ];
    }
}
