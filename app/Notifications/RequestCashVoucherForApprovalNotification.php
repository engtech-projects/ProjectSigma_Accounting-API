<?php

namespace App\Notifications;

use App\Broadcasting\HrmsNotifyNextApproverChannel;
use App\Enums\ApprovalModels;
use App\Models\CashRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Notification;

class RequestCashVoucherForApprovalNotification extends Notification
{
    use Queueable;

    private $token;

    private $model;

    public $id;

    public function __construct($token, CashRequest $model)
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
        return [HrmsNotifyNextApproverChannel::class];
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
            'message' => 'A request for cash voucher that needs your approval.',
            'module' => 'Accounting',
            'request_type' => ApprovalModels::ACCOUNTING_CASH_REQUEST->name,
            'request_id' => $this->model->id,
            'action' => 'View',
        ];
    }
}
