<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProjection extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'class_name',
        'sort_order',
        'current_potential',
        'year_1_projection',
        'year_2_projection',
        'year_3_projection',
        'year_4_projection',
        'year_5_projection',
        'sections_required',
        'teachers_required',
        'capacity_utilization_pct',
    ];

    protected function casts(): array
    {
        return [
            'capacity_utilization_pct' => 'decimal:2',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }
}
