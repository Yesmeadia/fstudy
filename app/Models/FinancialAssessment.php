<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'total_capex',
        'annual_opex',
        'annual_revenue',
        'gross_surplus',
        'net_surplus',
        'ebitda',
        'break_even_students',
        'roi_percentage',
        'payback_period_years',
        'five_year_summary',
        'financial_notes',
    ];

    protected function casts(): array
    {
        return [
            'total_capex' => 'decimal:2',
            'annual_opex' => 'decimal:2',
            'annual_revenue' => 'decimal:2',
            'gross_surplus' => 'decimal:2',
            'net_surplus' => 'decimal:2',
            'ebitda' => 'decimal:2',
            'roi_percentage' => 'decimal:2',
            'payback_period_years' => 'decimal:1',
            'five_year_summary' => 'array',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
