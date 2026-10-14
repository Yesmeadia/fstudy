<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\CapitalExpense;
use App\Models\DemographicAssessment;
use App\Models\DocumentRequirement;
use App\Models\FeasibilityApplication;
use App\Models\InfrastructureAssessment;
use App\Models\InfrastructureCategory;
use App\Models\LandDetail;
use App\Models\Location;
use App\Models\NearbySchool;
use App\Models\OperatingExpense;
use App\Models\RevenueProjection;
use App\Models\StaffingAssessment;
use App\Models\StaffingPosition;
use App\Models\StudentProjection;
use App\Services\Feasibility\DemographicCalculationService;
use App\Services\Feasibility\DocumentVerificationService;
use App\Services\Feasibility\FinancialCalculationService;
use App\Services\Feasibility\StudentProjectionService;
use App\Services\Feasibility\WorkflowService;
use Illuminate\Http\Request;

class FeasibilityWizardController extends Controller
{
    public function __construct(
        protected DemographicCalculationService $demographicService,
        protected StudentProjectionService $projectionService,
        protected FinancialCalculationService $financialService,
        protected DocumentVerificationService $documentService,
        protected WorkflowService $workflowService,
    ) {}

    public function showStep(FeasibilityApplication $application, int $step = 1)
    {
        $this->authorizeAccess($application);

        $step = max(1, min(12, $step));

        // Load data needed for specific steps
        $school = $application->school;
        $location = $application->location;
        $land = $application->landDetail;
        $demographic = $application->demographic;
        $nearbySchools = $application->nearbySchools;
        $projections = $application->studentProjections;
        $infrastructureCategories = InfrastructureCategory::with(['items.assessments' => fn ($q) => $q->where('application_id', $application->id)])->orderBy('sort_order')->get();
        $staffingPositions = StaffingPosition::orderBy('sort_order')->get();
        $staffingAssessments = $application->staffingAssessments->keyBy('position_id');
        $financialAssessment = $application->financialAssessment;
        $capitalExpenses = $application->capitalExpenses;
        $operatingExpenses = $application->operatingExpenses;
        $revenueProjections = $application->revenueProjections;
        $documentRequirements = DocumentRequirement::orderBy('sort_order')->get();
        $documents = $application->documents->keyBy('requirement_id');

        // Missing requirements for review
        $missingDocuments = $this->documentService->getMissingMandatoryRequirements($application);

        // Calculate progress percentage based on completed steps
        $progress = $this->calculateCompletionPercentage($application);

        return view('applications.wizard.step', compact(
            'application',
            'step',
            'school',
            'location',
            'land',
            'demographic',
            'nearbySchools',
            'projections',
            'infrastructureCategories',
            'staffingPositions',
            'staffingAssessments',
            'financialAssessment',
            'capitalExpenses',
            'operatingExpenses',
            'revenueProjections',
            'documentRequirements',
            'documents',
            'missingDocuments',
            'progress'
        ));
    }

    public function saveStep(Request $request, FeasibilityApplication $application, int $step)
    {
        $this->authorizeAccess($application);

        if (! $application->canBeEdited()) {
            return back()->with('error', 'This application is under review or approved and cannot be modified.');
        }

        switch ($step) {
            case 1:
                $data = $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'proposed_name' => ['nullable', 'string', 'max:255'],
                    'board' => ['required', 'string'],
                    'medium' => ['required', 'string', 'max:50'],
                    'school_type' => ['required', 'string'],
                    'established_year' => ['nullable', 'integer'],
                    'is_existing_school' => ['boolean'],
                ]);
                $application->school->update($data);
                break;

            case 2:
                $data = $request->validate([
                    'applicant_name' => ['required', 'string', 'max:255'],
                    'applicant_email' => ['required', 'email', 'max:255'],
                    'applicant_phone' => ['required', 'string', 'max:30'],
                    'management_organization' => ['required', 'string', 'max:255'],
                    'trust_society_type' => ['required', 'string'],
                    'registration_number' => ['nullable', 'string', 'max:100'],
                    'registration_date' => ['nullable', 'date'],
                    'website' => ['nullable', 'url'],
                ]);
                $application->school->update($data);
                break;

            case 3:
                $data = $request->validate([
                    'state' => ['required', 'string'],
                    'district' => ['required', 'string'],
                    'taluk_tehsil' => ['nullable', 'string'],
                    'village_town' => ['required', 'string'],
                    'address' => ['required', 'string'],
                    'pincode' => ['required', 'string'],
                    'latitude' => ['nullable', 'numeric'],
                    'longitude' => ['nullable', 'numeric'],
                    'nearest_major_road' => ['nullable', 'string'],
                    'distance_from_main_road_km' => ['nullable', 'numeric'],
                    'accessibility_description' => ['nullable', 'string'],
                    'public_transport_available' => ['boolean'],
                    'transport_details' => ['nullable', 'string'],
                    'total_land_area' => ['required', 'numeric', 'min:0.1'],
                    'area_unit' => ['required', 'string'],
                    'ownership_type' => ['required', 'string'],
                    'survey_number' => ['nullable', 'string'],
                    'land_registration_details' => ['nullable', 'string'],
                    'lease_start_date' => ['nullable', 'date'],
                    'lease_expiry_date' => ['nullable', 'date'],
                    'building_area' => ['nullable', 'numeric'],
                    'playground_area' => ['nullable', 'numeric'],
                    'open_area' => ['nullable', 'numeric'],
                    'is_land_title_clear' => ['boolean'],
                    'land_remarks' => ['nullable', 'string'],
                ]);

                Location::updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'state' => $data['state'],
                        'district' => $data['district'],
                        'taluk_tehsil' => $data['taluk_tehsil'] ?? null,
                        'village_town' => $data['village_town'],
                        'address' => $data['address'],
                        'pincode' => $data['pincode'],
                        'latitude' => $data['latitude'] ?? null,
                        'longitude' => $data['longitude'] ?? null,
                        'nearest_major_road' => $data['nearest_major_road'] ?? null,
                        'distance_from_main_road_km' => $data['distance_from_main_road_km'] ?? null,
                        'accessibility_description' => $data['accessibility_description'] ?? null,
                        'public_transport_available' => $request->boolean('public_transport_available', true),
                        'transport_details' => $data['transport_details'] ?? null,
                    ]
                );

                LandDetail::updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'total_land_area' => $data['total_land_area'],
                        'area_unit' => $data['area_unit'],
                        'ownership_type' => $data['ownership_type'],
                        'survey_number' => $data['survey_number'] ?? null,
                        'land_registration_details' => $data['land_registration_details'] ?? null,
                        'lease_start_date' => $data['lease_start_date'] ?? null,
                        'lease_expiry_date' => $data['lease_expiry_date'] ?? null,
                        'building_area' => $data['building_area'] ?? null,
                        'playground_area' => $data['playground_area'] ?? null,
                        'open_area' => $data['open_area'] ?? null,
                        'is_land_title_clear' => $request->boolean('is_land_title_clear', true),
                        'remarks' => $data['land_remarks'] ?? null,
                    ]
                );
                break;

            case 4:
                $data = $request->validate([
                    'population_1km' => ['required', 'integer', 'min:0'],
                    'population_3km' => ['required', 'integer', 'min:0'],
                    'population_5km' => ['required', 'integer', 'min:0'],
                    'number_of_households' => ['required', 'integer', 'min:0'],
                    'children_age_3_5' => ['required', 'integer', 'min:0'],
                    'children_age_6_10' => ['required', 'integer', 'min:0'],
                    'children_age_11_14' => ['required', 'integer', 'min:0'],
                    'children_age_15_18' => ['required', 'integer', 'min:0'],
                    'population_growth_rate' => ['required', 'numeric'],
                    'residential_development_trend' => ['required', 'string'],
                    'employment_profile' => ['required', 'string'],
                    'economic_profile' => ['required', 'string'],
                    'expected_capture_rate' => ['required', 'numeric', 'min:0.1', 'max:100'],
                    'remarks' => ['nullable', 'string'],
                ]);

                $demographic = DemographicAssessment::updateOrCreate(
                    ['application_id' => $application->id],
                    $data
                );
                $this->demographicService->updateAssessmentPotential($demographic);
                break;

            case 5:
                if ($request->has('nearby_school_name')) {
                    $nsData = $request->validate([
                        'nearby_school_name' => ['required', 'string'],
                        'distance_km' => ['required', 'numeric'],
                        'board' => ['required', 'string'],
                        'medium' => ['required', 'string'],
                        'classes_offered' => ['required', 'string'],
                        'student_strength' => ['required', 'integer'],
                        'fee_structure_annual' => ['required', 'numeric'],
                        'competition_level' => ['required', 'string'],
                    ]);

                    NearbySchool::create([
                        'application_id' => $application->id,
                        'school_name' => $nsData['nearby_school_name'],
                        'distance_km' => $nsData['distance_km'],
                        'board' => $nsData['board'],
                        'medium' => $nsData['medium'],
                        'classes_offered' => $nsData['classes_offered'],
                        'student_strength' => $nsData['student_strength'],
                        'fee_structure_annual' => $nsData['fee_structure_annual'],
                        'competition_level' => $nsData['competition_level'],
                    ]);
                }
                break;

            case 6:
                $projData = $request->validate([
                    'projections' => ['required', 'array'],
                    'projections.*.class_name' => ['required', 'string'],
                    'projections.*.current_potential' => ['required', 'integer'],
                    'projections.*.year_1' => ['required', 'integer'],
                    'projections.*.year_2' => ['required', 'integer'],
                    'projections.*.year_3' => ['required', 'integer'],
                    'projections.*.year_4' => ['required', 'integer'],
                    'projections.*.year_5' => ['required', 'integer'],
                ]);

                foreach ($projData['projections'] as $idx => $p) {
                    $reqs = $this->projectionService->computeRequirements((int) $p['year_1']);
                    StudentProjection::updateOrCreate(
                        ['application_id' => $application->id, 'class_name' => $p['class_name']],
                        [
                            'sort_order' => $idx + 1,
                            'current_potential' => $p['current_potential'],
                            'year_1_projection' => $p['year_1'],
                            'year_2_projection' => $p['year_2'],
                            'year_3_projection' => $p['year_3'],
                            'year_4_projection' => $p['year_4'],
                            'year_5_projection' => $p['year_5'],
                            'sections_required' => $reqs['sections_required'],
                            'teachers_required' => $reqs['teachers_required'],
                            'capacity_utilization_pct' => $reqs['capacity_utilization_pct'],
                        ]
                    );
                }
                break;

            case 7:
                $infraData = $request->validate([
                    'items' => ['required', 'array'],
                    'items.*.item_id' => ['required', 'exists:infrastructure_items,id'],
                    'items.*.status' => ['required', 'in:available,not_available,planned,not_applicable'],
                    'items.*.quantity' => ['nullable', 'integer'],
                    'items.*.remarks' => ['nullable', 'string'],
                ]);

                foreach ($infraData['items'] as $infra) {
                    InfrastructureAssessment::updateOrCreate(
                        ['application_id' => $application->id, 'item_id' => $infra['item_id']],
                        [
                            'status' => $infra['status'],
                            'quantity' => $infra['quantity'] ?? 0,
                            'score' => $infra['status'] === 'available' ? 10.0 : ($infra['status'] === 'planned' ? 6.0 : 0.0),
                            'remarks' => $infra['remarks'] ?? null,
                        ]
                    );
                }
                break;

            case 8:
                $staffData = $request->validate([
                    'staffing' => ['required', 'array'],
                    'staffing.*.position_id' => ['required', 'exists:staffing_positions,id'],
                    'staffing.*.required_qty' => ['required', 'integer'],
                    'staffing.*.existing_qty' => ['required', 'integer'],
                    'staffing.*.monthly_salary' => ['required', 'numeric'],
                ]);

                foreach ($staffData['staffing'] as $st) {
                    $additional = max(0, $st['required_qty'] - $st['existing_qty']);
                    $annualCost = $additional * $st['monthly_salary'] * 12;

                    StaffingAssessment::updateOrCreate(
                        ['application_id' => $application->id, 'position_id' => $st['position_id']],
                        [
                            'required_qty' => $st['required_qty'],
                            'existing_qty' => $st['existing_qty'],
                            'additional_qty' => $additional,
                            'monthly_salary' => $st['monthly_salary'],
                            'annual_cost' => $annualCost,
                        ]
                    );
                }
                break;

            case 9:
                // Capex entry
                if ($request->has('capex_name') && $request->filled('capex_name')) {
                    CapitalExpense::create([
                        'application_id' => $application->id,
                        'category' => $request->input('capex_category', 'other'),
                        'item_name' => $request->input('capex_name'),
                        'amount' => $request->input('capex_amount', 0),
                        'remarks' => $request->input('capex_remarks'),
                    ]);
                }

                // Opex entry
                if ($request->has('opex_name') && $request->filled('opex_name')) {
                    OperatingExpense::create([
                        'application_id' => $application->id,
                        'category' => $request->input('opex_category', 'miscellaneous'),
                        'item_name' => $request->input('opex_name'),
                        'annual_amount' => $request->input('opex_amount', 0),
                        'remarks' => $request->input('opex_remarks'),
                    ]);
                }

                // Revenue projections
                if ($request->has('revenue_type') && $request->filled('revenue_type')) {
                    RevenueProjection::create([
                        'application_id' => $application->id,
                        'fee_type' => $request->input('revenue_type'),
                        'fee_per_student' => $request->input('fee_per_student', 0),
                        'annual_total' => $request->input('rev_year_1', 0),
                        'year_1' => $request->input('rev_year_1', 0),
                        'year_2' => $request->input('rev_year_2', 0),
                        'year_3' => $request->input('rev_year_3', 0),
                        'year_4' => $request->input('rev_year_4', 0),
                        'year_5' => $request->input('rev_year_5', 0),
                    ]);
                }

                // Recompute model
                $this->financialService->computeFinancialModel($application);
                break;

            case 10:
                // Documents are uploaded via AJAX / dedicated upload endpoint
                break;

            case 11:
                $data = $request->validate([
                    'declaration_accepted' => ['accepted'],
                    'declaration_signed_by' => ['required', 'string', 'max:255'],
                ]);
                $application->update([
                    'declaration_accepted' => true,
                    'declaration_signed_by' => $data['declaration_signed_by'],
                    'declaration_date' => now(),
                ]);
                break;

            case 12:
                // Submission action handled in submit() method
                break;
        }

        // Advance current step if progressing forward
        $nextStep = min(12, $step + 1);
        $completionPct = $this->calculateCompletionPercentage($application);

        $application->update([
            'current_step' => max($application->current_step, $nextStep),
            'completion_percentage' => $completionPct,
        ]);

        if ($request->input('action') === 'save_draft') {
            return redirect()->route('applications.wizard.step', [$application, $step])
                ->with('success', 'Draft saved successfully. You may continue at any time.');
        }

        if ($request->input('action') === 'add_item') {
            return redirect()->route('applications.wizard.step', [$application, $step])
                ->with('success', 'Item added successfully.');
        }

        return redirect()->route('applications.wizard.step', [$application, $nextStep])
            ->with('success', "Step {$step} completed.");
    }

    public function deleteNearbySchool(FeasibilityApplication $application, NearbySchool $school)
    {
        $this->authorizeAccess($application);
        if ($school->application_id !== $application->id) {
            abort(404);
        }
        $school->delete();

        return back()->with('success', 'Nearby school record removed.');
    }

    public function deleteCapex(FeasibilityApplication $application, CapitalExpense $expense)
    {
        $this->authorizeAccess($application);
        if ($expense->application_id !== $application->id) {
            abort(404);
        }
        $expense->delete();
        $this->financialService->computeFinancialModel($application);

        return back()->with('success', 'Capital expense item removed.');
    }

    public function deleteOpex(FeasibilityApplication $application, OperatingExpense $expense)
    {
        $this->authorizeAccess($application);
        if ($expense->application_id !== $application->id) {
            abort(404);
        }
        $expense->delete();
        $this->financialService->computeFinancialModel($application);

        return back()->with('success', 'Operating expense item removed.');
    }

    public function deleteRevenue(FeasibilityApplication $application, RevenueProjection $revenue)
    {
        $this->authorizeAccess($application);
        if ($revenue->application_id !== $application->id) {
            abort(404);
        }
        $revenue->delete();
        $this->financialService->computeFinancialModel($application);

        return back()->with('success', 'Revenue item removed.');
    }

    public function submit(Request $request, FeasibilityApplication $application)
    {
        $this->authorizeAccess($application);

        if (! $application->canBeEdited()) {
            return back()->with('error', 'Application has already been submitted.');
        }

        // Validate mandatory declaration
        if (! $application->declaration_accepted) {
            return redirect()->route('applications.wizard.step', [$application, 11])
                ->with('error', 'You must accept the legal declaration before submission.');
        }

        // Validate mandatory documents
        $missingDocs = $this->documentService->getMissingMandatoryRequirements($application);
        if (! empty($missingDocs)) {
            return redirect()->route('applications.wizard.step', [$application, 10])
                ->with('error', 'Please upload all mandatory documents before submitting.');
        }

        // Transition status to submitted
        $this->workflowService->transitionStatus(
            $application,
            ApplicationStatus::SUBMITTED,
            'Feasibility study application submitted by applicant for preliminary review.'
        );

        $application->update([
            'current_step' => 12,
            'completion_percentage' => 100,
        ]);

        return redirect()->route('applications.show', $application)
            ->with('success', 'Congratulations! Your Feasibility Study Application has been submitted successfully.');
    }

    protected function calculateCompletionPercentage(FeasibilityApplication $application): int
    {
        $score = 0;
        if ($application->school) {
            $score += 10;
        }
        if ($application->location) {
            $score += 10;
        }
        if ($application->landDetail) {
            $score += 10;
        }
        if ($application->demographic) {
            $score += 10;
        }
        if ($application->nearbySchools()->exists()) {
            $score += 10;
        }
        if ($application->studentProjections()->exists()) {
            $score += 10;
        }
        if ($application->infrastructureAssessments()->exists()) {
            $score += 10;
        }
        if ($application->staffingAssessments()->exists()) {
            $score += 10;
        }
        if ($application->capitalExpenses()->exists() || $application->operatingExpenses()->exists()) {
            $score += 10;
        }
        if ($application->documents()->exists()) {
            $score += 5;
        }
        if ($application->declaration_accepted) {
            $score += 5;
        }

        return min(100, $score);
    }

    protected function authorizeAccess(FeasibilityApplication $application): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(401);
        }

        if ($user->isSuperAdmin()) {
            return;
        }

        if ($user->isInspector()) {
            if ($application->assigned_to !== $user->id &&
                $application->applicant_user_id !== $user->id &&
                ! $user->hasPermissionTo('applications.edit_any')) {
                abort(403, 'Unauthorized access to this application wizard.');
            }

            return;
        }

        if ($application->applicant_user_id !== $user->id) {
            abort(403, 'Unauthorized access to this application wizard.');
        }
    }
}
