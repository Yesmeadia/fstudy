<?php

namespace App\Services\Feasibility;

use App\Models\FeasibilityApplication;
use App\Models\FinancialAssessment;

class FinancialCalculationService
{
    /**
     * Compute comprehensive financial metrics for an application.
     */
    public function computeFinancialModel(FeasibilityApplication $application): FinancialAssessment
    {
        // Total Capex
        $totalCapex = (float) $application->capitalExpenses()->sum('amount');

        // Total Annual Opex (includes operating expenses + staffing HR costs)
        $opexExpenses = (float) $application->operatingExpenses()->sum('annual_amount');
        $hrStaffingCost = (float) $application->staffingAssessments()->sum('annual_cost');
        $totalAnnualOpex = $opexExpenses + $hrStaffingCost;

        // Total Annual Revenue (from Year 1 projections)
        $totalAnnualRevenue = (float) $application->revenueProjections()->sum('year_1');
        if ($totalAnnualRevenue <= 0) {
            $totalAnnualRevenue = (float) $application->revenueProjections()->sum('annual_total');
        }

        // Gross Surplus = Revenue - Opex
        $grossSurplus = $totalAnnualRevenue - $totalAnnualOpex;

        // Standard depreciation (assume 5% on Capex) and tax/reserve (assume 10% on positive gross surplus)
        $depreciation = $totalCapex * 0.05;
        $ebitda = $grossSurplus; // Operating surplus before interest, tax, depreciation
        $netSurplus = $ebitda - $depreciation;

        // Average fee per student to calculate break-even student count
        $year1Students = (int) $application->studentProjections()->sum('year_1_projection');
        $avgFeePerStudent = $year1Students > 0 ? ($totalAnnualRevenue / $year1Students) : 50000;
        $breakEvenStudents = $avgFeePerStudent > 0 ? (int) ceil($totalAnnualOpex / $avgFeePerStudent) : 0;

        // ROI % = (Net Surplus / Total Capex) * 100
        $roi = $totalCapex > 0 ? round(($netSurplus / $totalCapex) * 100, 2) : 0.00;

        // Payback Period in Years = Total Capex / EBITDA (or Net Surplus)
        $paybackYears = ($ebitda > 0 && $totalCapex > 0) ? round($totalCapex / $ebitda, 1) : 0.0;

        // 5-Year Projection Summary
        $fiveYearSummary = $this->generateFiveYearProjection($application, $totalCapex, $totalAnnualOpex);

        $assessment = $application->financialAssessment ?? new FinancialAssessment(['application_id' => $application->id]);
        $assessment->total_capex = $totalCapex;
        $assessment->annual_opex = $totalAnnualOpex;
        $assessment->annual_revenue = $totalAnnualRevenue;
        $assessment->gross_surplus = $grossSurplus;
        $assessment->net_surplus = $netSurplus;
        $assessment->ebitda = $ebitda;
        $assessment->break_even_students = $breakEvenStudents;
        $assessment->roi_percentage = $roi;
        $assessment->payback_period_years = $paybackYears;
        $assessment->five_year_summary = $fiveYearSummary;
        $assessment->save();

        return $assessment;
    }

    /**
     * Generate 5-year financial trajectory based on student projections and inflation.
     */
    public function generateFiveYearProjection(
        FeasibilityApplication $application,
        float $initialCapex,
        float $baseOpex
    ): array {
        $projections = $application->studentProjections;
        $years = [
            1 => (int) $projections->sum('year_1_projection'),
            2 => (int) $projections->sum('year_2_projection'),
            3 => (int) $projections->sum('year_3_projection'),
            4 => (int) $projections->sum('year_4_projection'),
            5 => (int) $projections->sum('year_5_projection'),
        ];

        $summary = [];
        $opexInflationRate = 0.07; // 7% annual inflation on expenses
        $feeEscalationRate = 0.08; // 8% annual fee increase

        $baseRev1 = (float) $application->revenueProjections()->sum('year_1');
        $baseRevPerStudent = $years[1] > 0 ? ($baseRev1 / $years[1]) : 50000;

        $cumulativeSurplus = 0;

        for ($year = 1; $year <= 5; $year++) {
            $studentCount = $years[$year] > 0 ? $years[$year] : ($years[1] * (1 + ($year - 1) * 0.15));
            $feePerStudent = $baseRevPerStudent * pow(1 + $feeEscalationRate, $year - 1);
            $revenue = round($studentCount * $feePerStudent, 2);

            // Variable opex tied slightly to student growth + inflation
            $opex = round($baseOpex * pow(1 + $opexInflationRate, $year - 1) * (1 + (($studentCount - $years[1]) * 0.001)), 2);
            $ebitda = round($revenue - $opex, 2);
            $netSurplus = round($ebitda - ($initialCapex * 0.05), 2);
            $cumulativeSurplus += $netSurplus;

            $summary[] = [
                'year' => $year,
                'students' => (int) round($studentCount),
                'revenue' => $revenue,
                'opex' => $opex,
                'ebitda' => $ebitda,
                'net_surplus' => $netSurplus,
                'cumulative_surplus' => round($cumulativeSurplus, 2),
            ];
        }

        return $summary;
    }
}
