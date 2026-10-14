<?php

namespace App\Enums;

enum DocumentStatus: string
{
    case PENDING = 'pending';
    case UPLOADED = 'uploaded';
    case UNDER_VERIFICATION = 'under_verification';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Upload',
            self::UPLOADED => 'Uploaded',
            self::UNDER_VERIFICATION => 'Under Verification',
            self::VERIFIED => 'Verified',
            self::REJECTED => 'Rejected / Incomplete',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-slate-100 text-slate-700 border-slate-200',
            self::UPLOADED => 'bg-blue-50 text-blue-700 border-blue-200',
            self::UNDER_VERIFICATION => 'bg-amber-50 text-amber-700 border-amber-200',
            self::VERIFIED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::REJECTED => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
