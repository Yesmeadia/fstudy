<?php

namespace App\Enums;

enum RiskLevel: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low Risk',
            self::MEDIUM => 'Medium Risk',
            self::HIGH => 'High Risk',
            self::CRITICAL => 'Critical Risk',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::LOW => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::MEDIUM => 'bg-amber-50 text-amber-700 border-amber-200',
            self::HIGH => 'bg-orange-50 text-orange-700 border-orange-200',
            self::CRITICAL => 'bg-rose-50 text-rose-700 border-rose-200',
        };
    }
}
