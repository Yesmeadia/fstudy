<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemographicAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'population_1km',
        'population_3km',
        'population_5km',
        'number_of_households',
        'children_age_3_5',
        'children_age_6_10',
        'children_age_11_14',
        'children_age_15_18',
        'population_growth_rate',
        'residential_development_trend',
        'employment_profile',
        'economic_profile',
        'expected_capture_rate',
        'potential_students_calculated',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'population_growth_rate' => 'decimal:2',
            'expected_capture_rate' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function getTotalSchoolAgeChildrenAttribute(): int
    {
        return (int) $this->children_age_3_5 +
            (int) $this->children_age_6_10 +
            (int) $this->children_age_11_14 +
            (int) $this->children_age_15_18;
    }
}
