<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfrastructureAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'item_id',
        'status',
        'quantity',
        'score',
        'remarks',
        'photo_path',
        'document_path',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'score' => 'decimal:1',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(InfrastructureItem::class, 'item_id');
    }
}
