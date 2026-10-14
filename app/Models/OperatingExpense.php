<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperatingExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'category',
        'item_name',
        'annual_amount',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'annual_amount' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
