<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\FeasibilityApplication;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Query base depending on role: Inspector sees assigned/created, Super Admin sees all
        $appQuery = FeasibilityApplication::query();
        if ($user->isInspector() && ! $user->isSuperAdmin()) {
            $appQuery->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('applicant_user_id', $user->id);
            });
        }

        // Metrics
        $totalApps = (clone $appQuery)->count();
        $draftApps = (clone $appQuery)->where('status', ApplicationStatus::DRAFT)->count();
        $submittedApps = (clone $appQuery)->where('status', ApplicationStatus::SUBMITTED)->count();
        $underReviewApps = (clone $appQuery)->whereIn('status', [
            ApplicationStatus::SUBMITTED,
            ApplicationStatus::PRELIMINARY_REVIEW,
            ApplicationStatus::EVALUATION_IN_PROGRESS,
            ApplicationStatus::MANAGEMENT_REVIEW,
        ])->count();
        $approvedApps = (clone $appQuery)->where('status', ApplicationStatus::APPROVED)->count();
        $conditionallyApprovedApps = (clone $appQuery)->where('status', ApplicationStatus::CONDITIONALLY_APPROVED)->count();
        $rejectedApps = (clone $appQuery)->where('status', ApplicationStatus::REJECTED)->count();
        $returnedApps = (clone $appQuery)->where('status', ApplicationStatus::RETURNED_FOR_CORRECTION)->count();

        // Status Breakdown for Chart
        $statusCounts = (clone $appQuery)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Recent Applications
        $recentApplications = (clone $appQuery)
            ->with(['school', 'applicant', 'assignedOfficer', 'checklist'])
            ->latest()
            ->take(8)
            ->get();

        // Total Registered Schools
        $totalSchools = School::count();

        // Average Feasibility Score
        $avgScore = round((clone $appQuery)->whereNotNull('overall_score')->avg('overall_score') ?? 0, 1);

        // Pending Actions for Super Admin vs Inspector
        $pendingActions = [];
        if ($user->isSuperAdmin()) {
            $pendingActions['submitted_review'] = FeasibilityApplication::where('status', ApplicationStatus::SUBMITTED)->count();
        }
        if ($user->isInspector()) {
            $pendingActions['assigned_drafts'] = FeasibilityApplication::where('assigned_to', $user->id)
                ->whereIn('status', [ApplicationStatus::DRAFT, ApplicationStatus::RETURNED_FOR_CORRECTION])
                ->count();
        }

        return view('dashboard', compact(
            'totalApps',
            'draftApps',
            'submittedApps',
            'underReviewApps',
            'returnedApps',
            'approvedApps',
            'conditionallyApprovedApps',
            'rejectedApps',
            'totalSchools',
            'avgScore',
            'statusCounts',
            'recentApplications',
            'pendingActions'
        ));
    }
}
