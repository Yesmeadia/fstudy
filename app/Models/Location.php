<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'state',
        'district',
        'taluk_tehsil',
        'village_town',
        'address',
        'pincode',
        'latitude',
        'longitude',
        'nearest_major_road',
        'distance_from_main_road_km',
        'accessibility_description',
        'public_transport_available',
        'transport_details',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'distance_from_main_road_km' => 'decimal:2',
            'public_transport_available' => 'boolean',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
