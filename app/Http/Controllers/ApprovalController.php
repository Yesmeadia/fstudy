<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\ManagementApprovalRequest;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Services\Feasibility\WorkflowService;

class ApprovalController extends Controller
{
    public function __construct(
        protected WorkflowService $workflowService,
    ) {}

    public function decide(ManagementApprovalRequest $request, FeasibilityApplication $application)
    {
        $user = $request->user();
        $decision = $request->decision;
        $remarks = $request->remarks;

        $targetStatus = match ($decision) {
            'approved' => ApplicationStatus::APPROVED,
            'conditionally_approved' => ApplicationStatus::CONDITIONALLY_APPROVED,
            'returned_for_correction' => ApplicationStatus::RETURNED_FOR_CORRECTION,
            'rejected' => ApplicationStatus::REJECTED,
        };

        // Record management decision on application
        $application->management_decision = $decision;
        $application->management_decision_at = now();
        $application->management_decided_by = $user->id;
        $application->management_remarks = $remarks;
        $application->save();

        $this->workflowService->transitionStatus(
            $application,
            $targetStatus,
            "Management decision: {$targetStatus->label()}. Remarks: {$remarks}",
            $user
        );

        AuditLog::log('management_decision', $application, null, [
            'decision' => $decision,
            'remarks' => $remarks,
        ]);

        return back()->with('success', "Application decision recorded: {$targetStatus->label()}.");
    }
}
