<?php

namespace App\Models;

use App\Enums\BoardType;
use App\Enums\SchoolType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'proposed_name',
        'school_code',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'management_organization',
        'trust_society_type',
        'registration_number',
        'registration_date',
        'address',
        'state',
        'district',
        'city',
        'pincode',
        'latitude',
        'longitude',
        'website',
        'established_year',
        'is_existing_school',
        'board',
        'medium',
        'school_type',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'registration_date' => 'date',
            'is_existing_school' => 'boolean',
            'board' => BoardType::class,
            'school_type' => SchoolType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(FeasibilityApplication::class);
    }

    public function getFullLocationAttribute(): string
    {
        return "{$this->city}, {$this->district}, {$this->state} - {$this->pincode}";
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }

        return null;
    }
}
