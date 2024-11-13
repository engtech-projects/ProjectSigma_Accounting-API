<?php

namespace App\Enums;

enum FormStatus: string
{
	case DRAFT = 'draft';
	case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
	case ISSUED = 'issued';

	public function nextStatus(): ?FormStatus
    {
        return match($this) {
            self::DRAFT => self::PENDING,
            self::PENDING => self::APPROVED,
			self::REJECTED => self::REJECTED,
			self::APPROVED => self::ISSUED,
			self::ISSUED => null
        };
    }
}
