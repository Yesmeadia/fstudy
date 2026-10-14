<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\FeasibilityChecklist;
use App\Services\Feasibility\WorkflowService;
use Illuminate\Http\Request;

class FeasibilityChecklistController extends Controller
{
    public function __construct(
        protected WorkflowService $workflowService,
    ) {}

    /**
     * Authorize user access to this checklist.
     */
    protected function authorizeAccess(FeasibilityApplication $application, bool $mustBeEditable = false): void
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return;
        }

        if ($user->isInspector()) {
            if ($application->assigned_to !== $user->id && $application->applicant_user_id !== $user->id) {
                abort(403, 'You are not assigned to inspect this feasibility application.');
            }

            if ($mustBeEditable && ! $application->canBeEdited()) {
                abort(403, 'This feasibility checklist has already been submitted and is currently locked for editing.');
            }

            return;
        }

        abort(403, 'Unauthorized access.');
    }

    /**
     * Show the 10-section Checklist Data Collector form.
     */
    public function edit(Request $request, FeasibilityApplication $application)
    {
        $this->authorizeAccess($application);

        $school = $application->school;

        // Ensure checklist exists with default template
        $checklist = $application->checklist;
        $template = FeasibilityChecklist::defaultTemplate();

        if (! $checklist) {
            $checklist = $application->checklist()->create([
                'institution_name' => $school?->name ?? 'Unknown School',
                'location' => $school ? trim(($school->address ? $school->address.', ' : '').$school->city.', '.$school->state) : '—',
                'present_management' => $school?->management_organization ?? '—',
                'inspection_team' => $application->assignedOfficer?->name ?? auth()->user()->name,
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
        } else {
            // Self-heal: ensure existing checklist has all sections initialized
            $dirty = false;
            foreach ([
                'section1_legal', 'section2_infrastructure', 'section3_rooms',
                'section4_students', 'section5_enrolment', 'section6_academic',
                'section7_staff', 'section8_financial', 'section9_brand',
                'section10_primary_assessment', 'section11_secondary_assessment',
                'section12_teachers_assessment', 'section13_final_remarks',
            ] as $key) {
                $val = $checklist->{$key};
                if (empty($val)) {
                    $checklist->{$key} = $template[$key];
                    $dirty = true;
                }
            }
            if ($dirty) {
                $checklist->save();
            }
        }

        $complianceSummary = $checklist->getComplianceSummary();
        $evidenceSummary = $checklist->getEvidenceSummary();
        $activeTab = $request->query('section', empty($checklist->inspection_dates) ? '0' : '1');

        return view('applications.checklist.edit', compact(
            'application',
            'checklist',
            'school',
            'complianceSummary',
            'evidenceSummary',
            'activeTab'
        ));
    }

    /**
     * Save/Update checklist data (Draft or Complete).
     */
    public function update(Request $request, FeasibilityApplication $application)
    {
        $this->authorizeAccess($application, mustBeEditable: true);

        $checklist = $application->checklist ?? $application->checklist()->create(['application_id' => $application->id]);

        $data = $request->only([
            'institution_name',
            'location',
            'present_management',
            'inspection_dates',
            'inspection_team',
            'section1_legal',
            'section2_infrastructure',
            'section3_rooms',
            'section4_students',
            'section5_enrolment',
            'section6_academic',
            'section7_staff',
            'section8_financial',
            'section9_brand',
            'section10_primary_assessment',
            'section11_secondary_assessment',
            'section12_teachers_assessment',
            'section13_final_remarks',
            'overall_summary',
            'overall_recommendation',
            'inspector_name',
        ]);

        // Clean & Format Dynamic Room Rows if provided
        if (isset($data['section3_rooms']) && is_array($data['section3_rooms'])) {
            $data['section3_rooms'] = array_values(array_filter($data['section3_rooms'], function ($row) {
                return ! empty($row['type']) || ! empty($row['count']) || ! empty($row['size_feet']);
            }));
        }

        // Clean & Format Enrolment Statement Rows if provided
        if (isset($data['section5_enrolment']) && is_array($data['section5_enrolment'])) {
            $cleanedEnrolment = [];
            foreach ($data['section5_enrolment'] as $row) {
                $hasBoys = isset($row['boys']) && $row['boys'] !== '';
                $hasGirls = isset($row['girls']) && $row['girls'] !== '';
                $boys = $hasBoys ? (int) $row['boys'] : null;
                $girls = $hasGirls ? (int) $row['girls'] : null;

                $total = ($hasBoys || $hasGirls)
                    ? (($boys ?? 0) + ($girls ?? 0))
                    : (isset($row['total']) && $row['total'] !== '' ? (int) $row['total'] : null);

                $hasLast = isset($row['last_year_total']) && $row['last_year_total'] !== '';
                $lastYear = $hasLast ? (int) $row['last_year_total'] : null;

                $trend = '';
                if ($total !== null && $lastYear !== null) {
                    if ($total > $lastYear) {
                        $trend = 'Increased';
                    } elseif ($total < $lastYear) {
                        $trend = 'Decreased';
                    } else {
                        $trend = 'Stayed same';
                    }
                }

                $cleanedEnrolment[] = [
                    'class_division' => $row['class_division'] ?? '',
                    'boys' => $row['boys'] ?? '',
                    'girls' => $row['girls'] ?? '',
                    'total' => $total !== null ? (string) $total : '',
                    'last_year_total' => $row['last_year_total'] ?? '',
                    'trend' => $trend ?: ($row['trend'] ?? ''),
                ];
            }
            $data['section5_enrolment'] = $cleanedEnrolment;
        }

        // Clean & Format Section 14 Final Assessment Remarks (preserve attached photos if not re-submitted)
        if (isset($data['section13_final_remarks']) && is_array($data['section13_final_remarks'])) {
            $existingRemarks = $checklist->section13_final_remarks;
            if (is_array($existingRemarks) && ! empty($existingRemarks['photos']) && is_array($existingRemarks['photos'])) {
                $incomingPhotos = $data['section13_final_remarks']['photos'] ?? [];
                if (! is_array($incomingPhotos)) {
                    $incomingPhotos = [];
                }
                $mergedPhotos = $existingRemarks['photos'];
                foreach ($incomingPhotos as $k => $p) {
                    if (! empty($p)) {
                        $mergedPhotos[$k] = $p;
                    }
                }
                $data['section13_final_remarks']['photos'] = $mergedPhotos;
            }
        }

        $checklist->update($data);

        // Update application completion percentage based on questions answered
        $summary = $checklist->getComplianceSummary();
        $answered = $summary['yes'] + $summary['no'];
        $completionPct = $summary['total'] > 0 ? min(100, (int) round(($answered / $summary['total']) * 100)) : 10;
        $application->update([
            'completion_percentage' => max(10, $completionPct),
            'overall_score' => $summary['percentage'],
        ]);

        AuditLog::log('checklist_updated', $application, null, [
            'answered' => $answered,
            'compliance_rate' => $summary['percentage'],
            'action' => $request->input('action', 'save_draft'),
        ]);

        // If user pressed "Submit to Super Admin"
        if ($request->input('action') === 'submit') {
            if (empty($data['inspection_dates']) && empty($checklist->fresh()->inspection_dates)) {
                return redirect()->route('applications.checklist.edit', ['application' => $application, 'section' => 0])
                    ->with('error', 'Date of feasibility inspection is compulsory. Please record the inspection date before submitting.');
            }

            $checklist->update([
                'signed_at' => now(),
                'inspector_name' => $data['inspector_name'] ?: auth()->user()->name,
            ]);

            $this->workflowService->transitionStatus(
                application: $application,
                newStatus: ApplicationStatus::SUBMITTED,
                remarks: 'Checklist successfully submitted for Super Admin review by '.auth()->user()->name,
                user: auth()->user()
            );

            return redirect()->route('applications.show', $application)
                ->with('success', 'Feasibility inspection checklist submitted successfully to Super Admin for final review.');
        }

        $currentSection = $request->input('current_section', '0');
        $nextSection = min(15, ((int) $currentSection) + 1);
        $prevSection = max(0, ((int) $currentSection) - 1);

        if ($request->input('action') === 'save_and_next') {
            return redirect()->route('applications.checklist.edit', ['application' => $application, 'section' => $nextSection])
                ->with('success', "Section {$currentSection} saved. Progressing to Section {$nextSection}.");
        }

        if ($request->input('action') === 'save_and_prev') {
            return redirect()->route('applications.checklist.edit', ['application' => $application, 'section' => $prevSection])
                ->with('success', "Section {$currentSection} saved. Returned to Section {$prevSection}.");
        }

        return redirect()->route('applications.checklist.edit', ['application' => $application, 'section' => $currentSection])
            ->with('success', 'Checklist progress saved successfully as draft.');
    }

    /**
     * Upload an evidence photo for a checklist item.
     */
    public function uploadEvidencePhoto(Request $request, FeasibilityApplication $application)
    {
        $this->authorizeAccess($application, mustBeEditable: true);

        $request->validate([
            'photo' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp,heic', 'max:10240'],
        ]);

        $file = $request->file('photo');
        $path = $file->store("evidence_photos/{$application->id}", 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/'.$path),
            'filename' => $file->getClientOriginalName(),
        ]);
    }

    /**
     * Submit Checklist directly.
     */
    public function submit(Request $request, FeasibilityApplication $application)
    {
        $this->authorizeAccess($application, mustBeEditable: true);

        $request->validate([
            'inspector_name' => ['required', 'string', 'max:255'],
            'overall_recommendation' => ['required', 'string'],
        ]);

        $checklist = $application->checklist;
        if ($checklist) {
            $checklist->update([
                'inspector_name' => $request->inspector_name,
                'overall_recommendation' => $request->overall_recommendation,
                'overall_summary' => $request->overall_summary ?? $checklist->overall_summary,
                'signed_at' => now(),
            ]);
        }

        $this->workflowService->transitionStatus(
            application: $application,
            newStatus: ApplicationStatus::SUBMITTED,
            remarks: 'Checklist submitted to Super Admin by '.auth()->user()->name,
            user: auth()->user()
        );

        return redirect()->route('applications.show', $application)
            ->with('success', 'Feasibility inspection report submitted to Super Admin.');
    }
}
