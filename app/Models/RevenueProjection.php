<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueProjection extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'fee_type',
        'fee_per_student',
        'annual_total',
        'year_1',
        'year_2',
        'year_3',
        'year_4',
        'year_5',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'fee_per_student' => 'decimal:2',
            'annual_total' => 'decimal:2',
            'year_1' => 'decimal:2',
            'year_2' => 'decimal:2',
            'year_3' => 'decimal:2',
            'year_4' => 'decimal:2',
            'year_5' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
