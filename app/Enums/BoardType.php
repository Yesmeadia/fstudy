<?php

namespace App\Enums;

enum BoardType: string
{
    case CBSE = 'cbse';
    case ICSE = 'icse';
    case STATE_BOARD = 'state_board';
    case IB = 'ib';
    case CAMBRIDGE = 'cambridge';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CBSE => 'CBSE (Central Board of Secondary Education)',
            self::ICSE => 'CISCE / ICSE / ISC',
            self::STATE_BOARD => 'State Education Board',
            self::IB => 'International Baccalaureate (IB)',
            self::CAMBRIDGE => 'Cambridge International (CAIE)',
            self::OTHER => 'Other / Recognized Board',
        };
    }
}
