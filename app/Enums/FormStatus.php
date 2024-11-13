<?php

namespace App\Enums;

enum FormStatus: string
{
	case Draft = 'draft';
	case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
	case Issued = 'issued';

	public function nextStatus(): ?FormStatus
    {
        return match($this) {
            self::Draft => self::Pending,
            self::Pending => self::Approved,
			self::Rejected => self::Rejected,
			self::Approved => self::Issued,
			self::Issued => null
        };
    }
}
