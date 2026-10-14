<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\StoreFieldVisitRequest;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\FieldVisit;
use App\Models\FieldVisitItem;
use App\Services\Feasibility\WorkflowService;

class FieldVisitController extends Controller
{
    public function __construct(
        protected WorkflowService $workflowService,
    ) {}

    public function store(StoreFieldVisitRequest $request, FeasibilityApplication $application)
    {
        $user = $request->user();
        $data = $request->validated();

        $visit = FieldVisit::create([
            'application_id' => $application->id,
            'officer_id' => $user->id,
            'visit_date' => $data['visit_date'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'representative_name' => $data['representative_name'],
            'representative_contact' => $data['representative_contact'],
            'general_remarks' => $data['general_remarks'],
            'overall_recommendation' => $data['overall_recommendation'],
        ]);

        $defaultItems = [
            'land' => 'Land physical inspection & boundary verification',
            'building' => 'Building structural readiness & safety clearances',
            'location' => 'Highway connectivity & approach road width verification',
            'population' => 'Catchment residential density verification',
            'nearby_schools' => 'Distance to competing educational institutions',
            'infrastructure' => 'Classrooms, Science & Computer Labs audit',
            'documents' => 'Original title deeds and society bylaws scrutiny',
            'management' => 'Promoters interview & background integrity check',
        ];

        $submittedItems = $request->input('items', []);

        foreach ($defaultItems as $key => $name) {
            $isVerified = isset($submittedItems[$key]['is_verified']) && $submittedItems[$key]['is_verified'] == '1';
            $remarks = $submittedItems[$key]['remarks'] ?? null;

            FieldVisitItem::create([
                'field_visit_id' => $visit->id,
                'item_key' => $key,
                'item_name' => $name,
                'is_verified' => $isVerified,
                'remarks' => $remarks,
            ]);
        }

        // Update application status to field verification completed
        $this->workflowService->transitionStatus(
            $application,
            ApplicationStatus::FIELD_VERIFICATION_COMPLETED,
            "Field verification visit conducted by {$user->name}. Recommendation: ".ucfirst(str_replace('_', ' ', $data['overall_recommendation'])),
            $user
        );

        AuditLog::log('field_visit_conducted', $visit, null, ['recommendation' => $data['overall_recommendation']]);

        return back()->with('success', 'Field verification visit and checklist recorded successfully.');
    }
}
