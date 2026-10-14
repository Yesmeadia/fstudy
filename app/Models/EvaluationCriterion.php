<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCriterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weight_percentage',
        'max_score',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'weight_percentage' => 'decimal:2',
            'max_score' => 'decimal:2',
        ];
    }

    public function subcriteria(): HasMany
    {
        return $this->hasMany(EvaluationSubcriterion::class, 'criterion_id')->orderBy('sort_order');
    }
}
