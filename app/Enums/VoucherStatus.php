<?php

namespace App\Enums;

enum VoucherStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case VOID = 'void';
    case COMPLETED = 'completed';

    public function nextStatus(): ?VoucherStatus
    {
        return match ($this) {
            self::DRAFT => self::PENDING,
            self::PENDING => self::APPROVED,
            // self::PENDING => self::REJECTED,
            // self::PENDING => self::VOID,
            self::APPROVED => self::COMPLETED,
            self::COMPLETED => null
        };
    }
}
