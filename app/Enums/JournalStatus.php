<?php

namespace App\Enums;

enum JournalStatus: string
{
	case Draft = 'draft';
    case Posted = 'posted';
    case Open = 'open';
	case Void = 'void';

	public function nextStatus(): ?JournalStatus
    {
        return match($this) {
            self::Draft => self::Open,
            self::Open => self::Posted,
			self::Open => self::Void,
			self::Posted => null
        };
    }

}
