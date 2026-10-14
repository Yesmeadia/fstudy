<?php

namespace App\Enums;

enum ReviewStatus: string
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case NEEDS_CORRECTION = 'needs_correction';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::UNDER_REVIEW => 'Under Review',
            self::APPROVED => 'Approved',
            self::NEEDS_CORRECTION => 'Needs Correction',
            self::REJECTED => 'Rejected',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-slate-100 text-slate-700 border-slate-200',
            self::UNDER_REVIEW => 'bg-blue-50 text-blue-700 border-blue-200',
            self::APPROVED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::NEEDS_CORRECTION => 'bg-amber-50 text-amber-700 border-amber-200',
            self::REJECTED => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
