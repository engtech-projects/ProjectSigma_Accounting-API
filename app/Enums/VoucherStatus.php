<?php

namespace App\Enums;

enum VoucherStatus: string
{
	case Draft = 'draft';
	case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
	case Void = 'void';
	case Completed = 'completed';

	public function nextStatus(): ?VoucherStatus
    {
        return match($this) {
            self::Draft => self::Pending,
            self::Pending => self::Approved,
			self::Pending => self::Rejected,
			self::Pending => self::Void,
			self::Approved => self::Completed,
			self::Completed => null
        };
    }
}