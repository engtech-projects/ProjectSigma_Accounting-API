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
}