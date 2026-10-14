<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case PRELIMINARY_REVIEW = 'preliminary_review';
    case DOCUMENTS_PENDING = 'documents_pending';
    case DOCUMENTS_VERIFIED = 'documents_verified';
    case FIELD_VERIFICATION_PENDING = 'field_verification_pending';
    case FIELD_VERIFICATION_COMPLETED = 'field_verification_completed';
    case ACADEMIC_REVIEW = 'academic_review';
    case FINANCIAL_REVIEW = 'financial_review';
    case EVALUATION_IN_PROGRESS = 'evaluation_in_progress';
    case MANAGEMENT_REVIEW = 'management_review';
    case APPROVED = 'approved';
    case CONDITIONALLY_APPROVED = 'conditionally_approved';
    case REJECTED = 'rejected';
    case RETURNED_FOR_CORRECTION = 'returned_for_correction';
    case CANCELLED = 'cancelled';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SUBMITTED => 'Submitted',
            self::PRELIMINARY_REVIEW => 'Preliminary Review',
            self::DOCUMENTS_PENDING => 'Documents Pending',
            self::DOCUMENTS_VERIFIED => 'Documents Verified',
            self::FIELD_VERIFICATION_PENDING => 'Field Verification Pending',
            self::FIELD_VERIFICATION_COMPLETED => 'Field Verification Completed',
            self::ACADEMIC_REVIEW => 'Academic Review',
            self::FINANCIAL_REVIEW => 'Financial Review',
            self::EVALUATION_IN_PROGRESS => 'Evaluation In Progress',
            self::MANAGEMENT_REVIEW => 'Management Review',
            self::APPROVED => 'Approved',
            self::CONDITIONALLY_APPROVED => 'Conditionally Approved',
            self::REJECTED => 'Rejected',
            self::RETURNED_FOR_CORRECTION => 'Returned for Correction',
            self::CANCELLED => 'Cancelled',
            self::ARCHIVED => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::SUBMITTED, self::PRELIMINARY_REVIEW => 'blue',
            self::DOCUMENTS_PENDING, self::FIELD_VERIFICATION_PENDING => 'amber',
            self::DOCUMENTS_VERIFIED, self::FIELD_VERIFICATION_COMPLETED => 'teal',
            self::ACADEMIC_REVIEW, self::FINANCIAL_REVIEW, self::EVALUATION_IN_PROGRESS, self::MANAGEMENT_REVIEW => 'indigo',
            self::APPROVED => 'emerald',
            self::CONDITIONALLY_APPROVED => 'purple',
            self::REJECTED, self::CANCELLED => 'rose',
            self::RETURNED_FOR_CORRECTION => 'orange',
            self::ARCHIVED => 'slate',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-100 text-slate-700 border-slate-200',
            self::SUBMITTED, self::PRELIMINARY_REVIEW => 'bg-blue-50 text-blue-700 border-blue-200',
            self::DOCUMENTS_PENDING, self::FIELD_VERIFICATION_PENDING => 'bg-amber-50 text-amber-700 border-amber-200',
            self::DOCUMENTS_VERIFIED, self::FIELD_VERIFICATION_COMPLETED => 'bg-teal-50 text-teal-700 border-teal-200',
            self::ACADEMIC_REVIEW, self::FINANCIAL_REVIEW, self::EVALUATION_IN_PROGRESS, self::MANAGEMENT_REVIEW => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            self::APPROVED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::CONDITIONALLY_APPROVED => 'bg-purple-50 text-purple-700 border-purple-200',
            self::REJECTED, self::CANCELLED => 'bg-rose-50 text-rose-700 border-rose-200',
            self::RETURNED_FOR_CORRECTION => 'bg-orange-50 text-orange-700 border-orange-200',
            self::ARCHIVED => 'bg-slate-200 text-slate-800 border-slate-300',
        };
    }
}
