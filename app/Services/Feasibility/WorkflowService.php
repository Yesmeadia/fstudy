<?php

namespace App\Services\Feasibility;

use App\Enums\ApplicationStatus;
use App\Models\ApplicationStatusHistory;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\User;
use App\Notifications\ApplicationStatusChangedNotification;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    /**
     * Transition application to a new status, log history, record audit log, and notify stakeholders.
     */
    public function transitionStatus(
        FeasibilityApplication $application,
        ApplicationStatus $newStatus,
        ?string $remarks = null,
        ?User $user = null
    ): FeasibilityApplication {
        $actor = $user ?? auth()->user();
        $previousStatus = $application->status;

        DB::transaction(function () use ($application, $previousStatus, $newStatus, $remarks, $actor) {
            // 1. Update application status
            $application->status = $newStatus;
            if ($newStatus === ApplicationStatus::SUBMITTED && ! $application->submission_date) {
                $application->submission_date = now();
            }
            $application->save();

            // 2. Record status history
            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'previous_status' => $previousStatus,
                'new_status' => $newStatus,
                'changed_by' => $actor?->id,
                'remarks' => $remarks,
                'created_at' => now(),
            ]);

            // 3. Record Audit Log
            AuditLog::log(
                action: 'status_changed',
                model: $application,
                oldValues: ['status' => $previousStatus->value],
                newValues: ['status' => $newStatus->value, 'remarks' => $remarks]
            );

            // 4. Send Database Notification to applicant
            if ($application->applicant) {
                try {
                    $application->applicant->notify(
                        new ApplicationStatusChangedNotification($application, $previousStatus, $newStatus, $remarks)
                    );
                } catch (\Throwable $e) {
                    // Safe fallback if notifications table or mailer has temporary issue
                }
            }
        });

        return $application;
    }

    /**
     * Check if user is authorized to perform status transition.
     */
    public function canTransition(User $user, FeasibilityApplication $application, ApplicationStatus $targetStatus): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        if ($user->isInspector()) {
            return match ($targetStatus) {
                ApplicationStatus::SUBMITTED => ($application->assigned_to === $user->id || $application->applicant_user_id === $user->id) && $application->canBeEdited(),
                ApplicationStatus::DRAFT => ($application->assigned_to === $user->id || $application->applicant_user_id === $user->id),
                default => false,
            };
        }

        return false;
    }
}
