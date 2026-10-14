<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffingPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'default_monthly_salary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'default_monthly_salary' => 'decimal:2',
        ];
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(StaffingAssessment::class, 'position_id');
    }
}
