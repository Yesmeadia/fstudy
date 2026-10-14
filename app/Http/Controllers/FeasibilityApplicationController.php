<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\ApplicationStatusHistory;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\FeasibilityChecklist;
use App\Models\School;
use App\Models\User;
use App\Services\Feasibility\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeasibilityApplicationController extends Controller
{
    public function __construct(
        protected WorkflowService $workflowService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $query = FeasibilityApplication::with(['school', 'applicant', 'assignedOfficer', 'checklist'])->latest();

        if ($user->isInspector() && ! $user->isSuperAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                    ->orWhere('applicant_user_id', $user->id);
            });
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhereHas('school', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%")
                            ->orWhere('district', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($state = $request->input('state')) {
            $query->whereHas('school', fn ($sq) => $sq->where('state', $state));
        }

        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        $applications = $query->paginate(12)->withQueryString();
        $officers = User::role('Inspector')->get();

        return view('applications.index', compact('applications', 'officers'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        if (! $user->isSuperAdmin() && ! $user->hasPermissionTo('applications.create')) {
            abort(403, 'Access denied. You do not have permission to initiate feasibility applications.');
        }

        $activeStatusQuery = fn ($q) => $q->where('status', '!=', ApplicationStatus::CANCELLED->value);

        $allSchools = ($user->isSuperAdmin() || $user->hasPermissionTo('schools.manage'))
            ? School::with(['applications' => $activeStatusQuery])->get()
            : School::where('user_id', $user->id)->with(['applications' => $activeStatusQuery])->get();

        // Separate schools with an active (non-cancelled) application from those without
        $schools = $allSchools->filter(fn ($s) => $s->applications->isEmpty())->values();
        $schoolsWithActiveApplications = $allSchools->filter(fn ($s) => $s->applications->isNotEmpty())->values();

        $inspectors = User::role('Inspector')->get(['id', 'name']);

        return view('applications.create', compact('schools', 'schoolsWithActiveApplications', 'inspectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'inspection_dates' => ['required', 'string', 'max:255'],
            'inspection_team' => ['required', 'string', 'max:500'],
            'institution_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'present_management' => ['required', 'string', 'max:255'],
        ], [
            'institution_name.required' => 'Institution / School name is required.',
            'location.required' => 'Campus location is required.',
            'present_management.required' => 'Present owner / management is required.',
            'inspection_dates.required' => 'Date(s) of Feasibility Inspection is compulsory before proceeding to the checklist.',
            'inspection_team.required' => 'Feasibility inspection team members must be recorded before proceeding.',
        ]);

        $user = $request->user();
        $school = School::findOrFail($request->school_id);

        if (! $user->isSuperAdmin() && ! $user->hasPermissionTo('schools.manage') && $school->user_id !== $user->id) {
            abort(403, 'You can only initiate applications for school profiles you manage.');
        }

        // Prevent duplicate: block if an active (non-cancelled) application already exists for this school
        $existingApplication = FeasibilityApplication::where('school_id', $school->id)
            ->where('status', '!=', ApplicationStatus::CANCELLED->value)
            ->first();

        if ($existingApplication) {
            return back()->withInput()->with(
                'error',
                "A feasibility report for {$school->name} already exists (#{$existingApplication->application_number}, status: {$existingApplication->status->label()}). Only one active report is allowed per school."
            );
        }

        $assignedTo = $request->assigned_to ?? ($user->isInspector() ? $user->id : null);

        $application = DB::transaction(function () use ($request, $school, $user, $assignedTo, &$appNumber) {
            $appNumber = FeasibilityApplication::generateApplicationNumber();

            $application = FeasibilityApplication::create([
                'application_number' => $appNumber,
                'school_id' => $school->id,
                'applicant_user_id' => $user->id,
                'assigned_to' => $assignedTo,
                'status' => ApplicationStatus::DRAFT,
                'current_step' => 1,
                'completion_percentage' => 10,
            ]);

            // Auto initialize the 13-section checklist from YES India Foundation template
            $template = FeasibilityChecklist::defaultTemplate();
            $application->checklist()->create([
                'institution_name' => $request->institution_name ?: $school->name,
                'location' => $request->location ?: trim(($school->address ? $school->address.', ' : '').$school->city.', '.$school->state),
                'present_management' => $request->present_management ?: $school->management_organization,
                'inspection_dates' => $request->inspection_dates,
                'inspection_team' => $request->inspection_team ?: ($application->assignedOfficer?->name ?? $user->name),
                'section1_legal' => $template['section1_legal'],
                'section2_infrastructure' => $template['section2_infrastructure'],
                'section3_rooms' => $template['section3_rooms'],
                'section4_students' => $template['section4_students'],
                'section5_enrolment' => $template['section5_enrolment'],
                'section6_academic' => $template['section6_academic'],
                'section7_staff' => $template['section7_staff'],
                'section8_financial' => $template['section8_financial'],
                'section9_brand' => $template['section9_brand'],
                'section10_primary_assessment' => $template['section10_primary_assessment'],
                'section11_secondary_assessment' => $template['section11_secondary_assessment'],
                'section12_teachers_assessment' => $template['section12_teachers_assessment'],
                'section13_final_remarks' => $template['section13_final_remarks'],
            ]);

            AuditLog::log('application_created', $application, null, ['application_number' => $appNumber]);

            return $application;
        });

        return redirect()->route('applications.checklist.edit', $application)
            ->with('success', "Feasibility inspection for {$school->name} ({$appNumber}) initiated. You can now fill out the checklist.");
    }

    public function show(Request $request, FeasibilityApplication $application)
    {
        $user = $request->user() ?? auth()->user();
        if (! $user) {
            abort(401);
        }

        // Authorization check: Super Admin has full access; Inspector/Users can only access assigned, created, or permitted applications
        if (! $user->isSuperAdmin()) {
            if ($application->assigned_to !== $user->id &&
                $application->applicant_user_id !== $user->id &&
                ! $user->hasPermissionTo('applications.view_any')) {
                abort(403, 'Unauthorized access to this feasibility application.');
            }
        }

        $application->loadMissing([
            'school',
            'applicant',
            'assignedOfficer',
            'checklist',
            'statusHistories.changer',
        ]);

        // Auto initialize checklist if missing
        if (! $application->checklist) {
            $template = FeasibilityChecklist::defaultTemplate();
            $school = $application->school;
            $application->checklist()->create([
                'institution_name' => $school?->name ?? 'Unknown School',
                'location' => $school ? trim(($school->address ? $school->address.', ' : '').$school->city.', '.$school->state) : '—',
                'present_management' => $school?->management_organization ?? '—',
                'inspection_team' => $application->assignedOfficer?->name ?? $user?->name ?? 'Inspection Team',
                'section1_legal' => $template['section1_legal'],
                'section2_infrastructure' => $template['section2_infrastructure'],
                'section3_rooms' => $template['section3_rooms'],
                'section4_students' => $template['section4_students'],
                'section5_enrolment' => $template['section5_enrolment'],
                'section6_academic' => $template['section6_academic'],
                'section7_staff' => $template['section7_staff'],
                'section8_financial' => $template['section8_financial'],
                'section9_brand' => $template['section9_brand'],
                'section10_primary_assessment' => $template['section10_primary_assessment'],
                'section11_secondary_assessment' => $template['section11_secondary_assessment'],
                'section12_teachers_assessment' => $template['section12_teachers_assessment'],
                'section13_final_remarks' => $template['section13_final_remarks'],
            ]);
            $application->load('checklist');
        }

        $availableOfficers = User::role('Inspector')->get();
        $complianceSummary = $application->checklist->getComplianceSummary();
        $checklist = $application->checklist;

        return view('applications.show', compact(
            'application',
            'checklist',
            'complianceSummary',
            'availableOfficers'
        ));
    }

    public function assign(Request $request, FeasibilityApplication $application)
    {
        $user = $request->user() ?? auth()->user();
        if (! $user || (! $user->isSuperAdmin() && ! $user->hasPermissionTo('applications.assign'))) {
            abort(403, 'Access denied. Only Super Admin or authorized managers can assign applications.');
        }

        $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $officer = User::findOrFail($request->assigned_to);
        $application->assigned_to = $officer->id;

        if ($application->status === ApplicationStatus::SUBMITTED) {
            $this->workflowService->transitionStatus(
                $application,
                ApplicationStatus::PRELIMINARY_REVIEW,
                "Assigned to officer {$officer->name}"
            );
        } else {
            $application->save();
        }

        AuditLog::log('application_assigned', $application, null, ['officer' => $officer->name]);

        return back()->with('success', "Application successfully assigned to {$officer->name}.");
    }

    public function updateStatus(Request $request, FeasibilityApplication $application)
    {
        $request->validate([
            'status' => ['required', 'string'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $status = ApplicationStatus::from($request->status);

        if (! $this->workflowService->canTransition($request->user(), $application, $status)) {
            abort(403, 'You do not have permission to transition this application to the selected status.');
        }

        $this->workflowService->transitionStatus($application, $status, $request->remarks, $request->user());

        return back()->with('success', "Application status updated to {$status->label()}.");
    }

    public function archive(FeasibilityApplication $application)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can archive applications.');

        $previousStatus = $application->status;
        $application->status = ApplicationStatus::ARCHIVED;
        $application->save();

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'previous_status' => $previousStatus,
            'new_status' => ApplicationStatus::ARCHIVED,
            'changed_by' => auth()->id(),
            'remarks' => 'Application archived by Super Admin.',
            'created_at' => now(),
        ]);

        AuditLog::log('application_archived', $application, ['status' => $previousStatus->value], ['status' => ApplicationStatus::ARCHIVED->value]);

        return back()->with('success', "Application #{$application->application_number} has been moved to Archive.");
    }

    public function unarchive(FeasibilityApplication $application)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can unarchive applications.');

        $lastHistory = $application->statusHistories()
            ->where('new_status', '!=', ApplicationStatus::ARCHIVED->value)
            ->first();

        $targetStatus = $lastHistory?->new_status ?? ApplicationStatus::DRAFT;

        $application->status = $targetStatus;
        $application->save();

        ApplicationStatusHistory::create([
            'application_id' => $application->id,
            'previous_status' => ApplicationStatus::ARCHIVED,
            'new_status' => $targetStatus,
            'changed_by' => auth()->id(),
            'remarks' => 'Application restored from archive by Super Admin.',
            'created_at' => now(),
        ]);

        AuditLog::log('application_unarchived', $application, ['status' => ApplicationStatus::ARCHIVED->value], ['status' => $targetStatus->value]);

        return back()->with('success', "Application #{$application->application_number} unarchived and restored to {$targetStatus->label()}.");
    }

    public function destroy(FeasibilityApplication $application)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Only Super Admin can delete feasibility applications.');

        $appNumber = $application->application_number;
        $application->delete();

        AuditLog::log('application_soft_deleted', $application, ['application_number' => $appNumber], null);

        return redirect()->route('applications.index')
            ->with('success', "Application #{$appNumber} moved to Recycle Bin (soft-deleted).");
    }

    public function edit(FeasibilityApplication $application)
    {
        return redirect()->route('applications.checklist.edit', $application);
    }
}
