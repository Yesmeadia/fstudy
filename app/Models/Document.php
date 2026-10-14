<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'requirement_id',
        'original_filename',
        'storage_path',
        'mime_type',
        'file_size_bytes',
        'uploaded_by',
        'version',
        'verification_status',
        'verified_by',
        'verified_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => DocumentStatus::class,
            'verified_at' => 'datetime',
            'file_size_bytes' => 'integer',
            'version' => 'integer',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(DocumentRequirement::class, 'requirement_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size_bytes;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        return number_format($bytes / 1024, 1).' KB';
    }
}
