<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'subcriterion_id',
        'score_obtained',
        'evaluator_id',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'score_obtained' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function subcriterion(): BelongsTo
    {
        return $this->belongsTo(EvaluationSubcriterion::class, 'subcriterion_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
