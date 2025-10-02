<?php

namespace App\Notifications;

use App\Broadcasting\HrmsNotifyUserChannel;
use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Notification;
use Str;

class RequestVoucherForDeniedNotification extends Notification
{
    use Queueable;
    private string $token;
    private Voucher $model;

    public function __construct(string $token, Voucher $model)
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
            'message' => 'A request for '. Str::headline($this->model->type) .' voucher has been DENIED.',
            'module' => 'Accounting',
            'request_type' =>  Str::headline($this->model->type),
            'request_id' => $this->model->id,
            'action' => 'View',
        ];
    }
}
