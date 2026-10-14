<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationSubcriterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'criterion_id',
        'name',
        'description',
        'max_score',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'max_score' => 'decimal:2',
        ];
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(EvaluationCriterion::class, 'criterion_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(EvaluationScore::class, 'subcriterion_id');
    }
}
