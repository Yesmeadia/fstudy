<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'officer_id',
        'visit_date',
        'latitude',
        'longitude',
        'representative_name',
        'representative_contact',
        'general_remarks',
        'overall_recommendation',
        'report_file_path',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(FieldVisitItem::class, 'field_visit_id');
    }
}
