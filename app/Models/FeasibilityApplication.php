<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\EvaluationResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeasibilityApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (FeasibilityApplication $application) {
            if (empty($application->application_number)) {
                $application->application_number = static::generateApplicationNumber();
            }
        });
    }

    /**
     * Generate a unique sequential application number (e.g. FS-2026-0001).
     * Defensively queries all existing records including soft-deleted ones to avoid unique constraint violations.
     */
    public static function generateApplicationNumber(): string
    {
        $year = date('Y');
        $prefix = "FS-{$year}-";

        // Query all existing application numbers for this year (including soft-deleted records)
        $existingNumbers = static::withTrashed()
            ->where('application_number', 'LIKE', $prefix.'%')
            ->pluck('application_number')
            ->toArray();

        $maxSeq = 0;
        foreach ($existingNumbers as $num) {
            if (preg_match('/^FS-\d{4}-(\d+)$/', (string) $num, $matches)) {
                $seq = (int) $matches[1];
                if ($seq > $maxSeq) {
                    $maxSeq = $seq;
                }
            }
        }

        $nextSeq = $maxSeq + 1;
        $appNumber = $prefix.str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);

        // Ensure collision freedom even with gaps or manual imports
        while (static::withTrashed()->where('application_number', $appNumber)->exists()) {
            $nextSeq++;
            $appNumber = $prefix.str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
        }

        return $appNumber;
    }

    protected $fillable = [
        'application_number',
        'school_id',
        'applicant_user_id',
        'assigned_to',
        'status',
        'current_step',
        'completion_percentage',
        'overall_score',
        'feasibility_rating',
        'submission_date',
        'declaration_accepted',
        'declaration_signed_by',
        'declaration_date',
        'management_decision',
        'management_decision_at',
        'management_decided_by',
        'management_remarks',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'current_step' => 'integer',
            'completion_percentage' => 'integer',
            'overall_score' => 'decimal:2',
            'submission_date' => 'datetime',
            'declaration_accepted' => 'boolean',
            'declaration_date' => 'datetime',
            'management_decision_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_user_id');
    }

    public function assignedOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function managementDecidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'management_decided_by');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class, 'application_id')->latest();
    }

    public function checklist(): HasOne
    {
        return $this->hasOne(FeasibilityChecklist::class, 'application_id');
    }

    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'application_id');
    }

    public function landDetail(): HasOne
    {
        return $this->hasOne(LandDetail::class, 'application_id');
    }

    public function demographic(): HasOne
    {
        return $this->hasOne(DemographicAssessment::class, 'application_id');
    }

    public function nearbySchools(): HasMany
    {
        return $this->hasMany(NearbySchool::class, 'application_id')->orderBy('distance_km');
    }

    public function studentProjections(): HasMany
    {
        return $this->hasMany(StudentProjection::class, 'application_id')->orderBy('sort_order');
    }

    public function infrastructureAssessments(): HasMany
    {
        return $this->hasMany(InfrastructureAssessment::class, 'application_id');
    }

    public function staffingAssessments(): HasMany
    {
        return $this->hasMany(StaffingAssessment::class, 'application_id');
    }

    public function financialAssessment(): HasOne
    {
        return $this->hasOne(FinancialAssessment::class, 'application_id');
    }

    public function capitalExpenses(): HasMany
    {
        return $this->hasMany(CapitalExpense::class, 'application_id');
    }

    public function operatingExpenses(): HasMany
    {
        return $this->hasMany(OperatingExpense::class, 'application_id');
    }

    public function revenueProjections(): HasMany
    {
        return $this->hasMany(RevenueProjection::class, 'application_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'application_id');
    }

    public function fieldVisits(): HasMany
    {
        return $this->hasMany(FieldVisit::class, 'application_id')->latest();
    }

    public function evaluationScores(): HasMany
    {
        return $this->hasMany(EvaluationScore::class, 'application_id');
    }

    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class, 'application_id')->orderByDesc('risk_score');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'application_id');
    }

    public function isDraft(): bool
    {
        return $this->status === ApplicationStatus::DRAFT;
    }

    public function isArchived(): bool
    {
        return $this->status === ApplicationStatus::ARCHIVED;
    }

    public function isSubmitted(): bool
    {
        return ! in_array($this->status, [ApplicationStatus::DRAFT, ApplicationStatus::CANCELLED, ApplicationStatus::ARCHIVED]);
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, [
            ApplicationStatus::DRAFT,
            ApplicationStatus::RETURNED_FOR_CORRECTION,
        ]);
    }

    public function getRatingAttribute(): ?EvaluationResult
    {
        if ($this->overall_score === null) {
            return null;
        }

        return EvaluationResult::fromScore((float) $this->overall_score);
    }
}
