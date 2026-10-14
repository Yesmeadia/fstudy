<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Services\Feasibility\ReportGenerationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        protected ReportGenerationService $reportService,
    ) {}

    public function downloadPdf(FeasibilityApplication $application)
    {
        $this->authorizeReportAccess($application);

        $pdf = $this->reportService->generatePdf($application);
        $filename = 'Feasibility_Study_Report_'.str_replace('-', '_', $application->application_number).'.pdf';

        AuditLog::log('report_downloaded_pdf', $application, null, ['filename' => $filename]);

        return $pdf->download($filename);
    }

    public function viewPdf(FeasibilityApplication $application)
    {
        $this->authorizeReportAccess($application);

        $pdf = $this->reportService->generatePdf($application);

        return $pdf->stream('Feasibility_Report.pdf');
    }

    protected function authorizeReportAccess(FeasibilityApplication $application): void
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
                ! $user->hasPermissionTo('reports.generate')) {
                abort(403, 'Unauthorized access to report.');
            }

            return;
        }

        if ($application->applicant_user_id !== $user->id) {
            abort(403, 'Unauthorized access to report.');
        }
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && ! $user->isManagement()) {
            abort(403);
        }

        AuditLog::log('applications_exported_csv', null, null, ['user' => $user->name]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="feasibility_applications_'.date('Ymd_His').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Application Number',
                'School Name',
                'Applicant Name',
                'State',
                'District',
                'Board',
                'School Type',
                'Status',
                'Feasibility Score (%)',
                'Rating',
                'Total Capex (INR)',
                'Annual Revenue (INR)',
                'Annual Opex (INR)',
                'ROI (%)',
                'Payback Period (Years)',
                'Assigned Officer',
                'Submission Date',
            ]);

            FeasibilityApplication::with(['school', 'applicant', 'assignedOfficer', 'financialAssessment'])
                ->chunk(100, function ($applications) use ($handle) {
                    foreach ($applications as $app) {
                        fputcsv($handle, [
                            $app->application_number,
                            $app->school?->name ?? 'N/A',
                            $app->applicant?->name ?? 'N/A',
                            $app->school?->state ?? 'N/A',
                            $app->school?->district ?? 'N/A',
                            $app->school?->board?->label() ?? 'N/A',
                            $app->school?->school_type?->label() ?? 'N/A',
                            $app->status->label(),
                            $app->overall_score ?? '0.00',
                            $app->feasibility_rating ? ucfirst(str_replace('_', ' ', $app->feasibility_rating)) : 'Unrated',
                            $app->financialAssessment?->total_capex ?? '0',
                            $app->financialAssessment?->annual_revenue ?? '0',
                            $app->financialAssessment?->annual_opex ?? '0',
                            $app->financialAssessment?->roi_percentage ?? '0',
                            $app->financialAssessment?->payback_period_years ?? '0',
                            $app->assignedOfficer?->name ?? 'Unassigned',
                            $app->submission_date?->format('Y-m-d H:i') ?? 'N/A',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
