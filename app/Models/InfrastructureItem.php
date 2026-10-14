<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InfrastructureItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'is_mandatory',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InfrastructureCategory::class, 'category_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(InfrastructureAssessment::class, 'item_id');
    }
}
