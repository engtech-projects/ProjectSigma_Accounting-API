<?php

namespace App\Enums;

enum JournalStatus: string
{
	case Draft = 'draft';
    case Posted = 'posted';
    case Open = 'open';
	case Void = 'void';
}
