<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldVisitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_visit_id',
        'item_key',
        'item_name',
        'is_verified',
        'remarks',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(FieldVisit::class, 'field_visit_id');
    }
}
