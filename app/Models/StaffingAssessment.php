<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffingAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'position_id',
        'required_qty',
        'existing_qty',
        'additional_qty',
        'monthly_salary',
        'annual_cost',
    ];

    protected function casts(): array
    {
        return [
            'monthly_salary' => 'decimal:2',
            'annual_cost' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(StaffingPosition::class, 'position_id');
    }
}
