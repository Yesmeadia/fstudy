<?php

namespace App\Enums;

enum OwnershipType: string
{
    case OWNED = 'owned';
    case LEASED = 'leased';
    case RENTED = 'rented';
    case TRUST_OWNED = 'trust_owned';
    case GOVERNMENT_ALLOTTED = 'government_allotted';

    public function label(): string
    {
        return match ($this) {
            self::OWNED => 'Freehold / Owned',
            self::LEASED => 'Registered Long-Term Lease (30+ yrs)',
            self::RENTED => 'Rental Agreement',
            self::TRUST_OWNED => 'Trust / Society Owned',
            self::GOVERNMENT_ALLOTTED => 'Government Allotted Land',
        };
    }
}
