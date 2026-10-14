<?php

namespace App\Models;

use App\Enums\RiskLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'category_id',
        'risk_description',
        'probability',
        'impact',
        'risk_score',
        'risk_level',
        'mitigation_strategy',
        'responsible_person',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'probability' => 'integer',
            'impact' => 'integer',
            'risk_score' => 'integer',
            'risk_level' => RiskLevel::class,
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RiskCategory::class, 'category_id');
    }

    public static function calculateLevel(int $score): RiskLevel
    {
        return match (true) {
            $score >= 16 => RiskLevel::CRITICAL,
            $score >= 10 => RiskLevel::HIGH,
            $score >= 5 => RiskLevel::MEDIUM,
            default => RiskLevel::LOW,
        };
    }
}
