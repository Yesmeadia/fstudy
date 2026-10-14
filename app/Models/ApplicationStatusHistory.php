<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'previous_status',
        'new_status',
        'changed_by',
        'remarks',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'previous_status' => ApplicationStatus::class,
            'new_status' => ApplicationStatus::class,
            'created_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
