<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapitalExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'category',
        'item_name',
        'amount',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
