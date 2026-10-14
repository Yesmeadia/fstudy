<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NearbySchool extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'school_name',
        'distance_km',
        'board',
        'medium',
        'classes_offered',
        'student_strength',
        'fee_structure_annual',
        'infrastructure_quality',
        'transportation_offered',
        'competition_level',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
            'fee_structure_annual' => 'decimal:2',
            'transportation_offered' => 'boolean',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
