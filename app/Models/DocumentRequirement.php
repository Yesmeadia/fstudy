<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_mandatory',
        'allowed_extensions',
        'max_size_kb',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
            'max_size_kb' => 'integer',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'requirement_id');
    }
}
