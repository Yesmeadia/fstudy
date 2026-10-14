<?php

namespace App\Enums;

enum SchoolType: string
{
    case K12 = 'k12';
    case PRIMARY = 'primary';
    case MIDDLE = 'middle';
    case SECONDARY = 'secondary';
    case SENIOR_SECONDARY = 'senior_secondary';
    case PLAY_SCHOOL = 'playschool';

    public function label(): string
    {
        return match ($this) {
            self::K12 => 'K-12 Composite School',
            self::PRIMARY => 'Primary School (Pre-Primary to 5)',
            self::MIDDLE => 'Middle School (Pre-Primary to 8)',
            self::SECONDARY => 'Secondary School (Pre-Primary to 10)',
            self::SENIOR_SECONDARY => 'Senior Secondary School (Pre-Primary to 12)',
            self::PLAY_SCHOOL => 'Play School / Pre-Primary',
        };
    }
}
