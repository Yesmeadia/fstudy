<?php

namespace App\Services\Feasibility;

use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportGenerationService
{
    /**
     * Build report view data array with relationships required for PDF.
     */
    public function compileReportData(FeasibilityApplication $application): array
    {
        $application->loadMissing([
            'school',
            'assignedOfficer',
            'checklist',
        ]);

        if (! $application->checklist) {
            $template = \App\Models\FeasibilityChecklist::defaultTemplate();
            $school = $application->school;
            $application->checklist()->create([
                'institution_name' => $school?->name ?? 'Unknown School',
                'location' => $school ? trim(($school->address ? $school->address.', ' : '').$school->city.', '.$school->state) : '—',
                'present_management' => $school?->management_organization ?? '—',
                'inspection_team' => $application->assignedOfficer?->name ?? 'Inspection Team',
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

        return [
            'application' => $application,
            'checklist' => $application->checklist,
            'school' => $application->school,
            'logoBase64' => file_exists(public_path('logob.png')) ? 'data:image/png;base64,'.base64_encode(file_get_contents(public_path('logob.png'))) : null,
        ];
    }

    /**
     * Generate DomPDF instance for the final feasibility study report.
     */
    public function generatePdf(FeasibilityApplication $application)
    {
        $data = $this->compileReportData($application);

        AuditLog::log(
            action: 'report_generated',
            model: $application,
            newValues: ['application_number' => $application->application_number]
        );

        $pdf = Pdf::loadView('reports.feasibility_study_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf;
    }
}
