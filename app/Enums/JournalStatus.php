<?php

namespace App\Enums;

enum JournalStatus: string
{
	case DRAFT = 'draft';
    case POSTED = 'posted';
    case OPEN = 'open';
	case VOID = 'void';

	public function nextStatus(): ?JournalStatus
    {
        return match($this) {
            self::DRAFT => self::OPEN,
            self::OPEN => self::POSTED,
			// self::Open => self::Void,
			self::POSTED => null
        };
    }

}
