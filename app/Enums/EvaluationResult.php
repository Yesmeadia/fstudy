<?php

namespace App\Enums;

enum EvaluationResult: string
{
    case NOT_FEASIBLE = 'not_feasible';
    case CONDITIONALLY_FEASIBLE = 'conditionally_feasible';
    case FEASIBLE = 'feasible';
    case HIGHLY_FEASIBLE = 'highly_feasible';

    public function label(): string
    {
        return match ($this) {
            self::NOT_FEASIBLE => 'Not Feasible (0–49%)',
            self::CONDITIONALLY_FEASIBLE => 'Conditionally Feasible (50–64%)',
            self::FEASIBLE => 'Feasible (65–79%)',
            self::HIGHLY_FEASIBLE => 'Highly Feasible (80–100%)',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::NOT_FEASIBLE => 'bg-rose-100 text-rose-800 border-rose-300',
            self::CONDITIONALLY_FEASIBLE => 'bg-amber-100 text-amber-800 border-amber-300',
            self::FEASIBLE => 'bg-blue-100 text-blue-800 border-blue-300',
            self::HIGHLY_FEASIBLE => 'bg-emerald-100 text-emerald-800 border-emerald-300',
        };
    }

    public static function fromScore(float $score): self
    {
        return match (true) {
            $score >= 80 => self::HIGHLY_FEASIBLE,
            $score >= 65 => self::FEASIBLE,
            $score >= 50 => self::CONDITIONALLY_FEASIBLE,
            default => self::NOT_FEASIBLE,
        };
    }
}
