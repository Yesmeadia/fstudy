<?php

namespace App\Models;

use App\Enums\OwnershipType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'total_land_area',
        'area_unit',
        'ownership_type',
        'survey_number',
        'land_registration_details',
        'lease_start_date',
        'lease_expiry_date',
        'building_area',
        'playground_area',
        'open_area',
        'is_land_title_clear',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'total_land_area' => 'decimal:2',
            'building_area' => 'decimal:2',
            'playground_area' => 'decimal:2',
            'open_area' => 'decimal:2',
            'lease_start_date' => 'date',
            'lease_expiry_date' => 'date',
            'is_land_title_clear' => 'boolean',
            'ownership_type' => OwnershipType::class,
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
