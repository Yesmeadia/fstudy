<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InfrastructureCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'icon',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InfrastructureItem::class, 'category_id')->orderBy('sort_order');
    }
}
