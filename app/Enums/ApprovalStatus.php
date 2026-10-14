<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case CONDITIONALLY_APPROVED = 'conditionally_approved';
    case RETURNED = 'returned';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::CONDITIONALLY_APPROVED => 'Conditionally Approved',
            self::RETURNED => 'Returned for Correction',
            self::REJECTED => 'Rejected',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-slate-100 text-slate-700 border-slate-200',
            self::APPROVED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::CONDITIONALLY_APPROVED => 'bg-purple-50 text-purple-700 border-purple-200',
            self::RETURNED => 'bg-amber-50 text-amber-700 border-amber-200',
            self::REJECTED => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
