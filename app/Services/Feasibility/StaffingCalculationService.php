<?php

namespace App\Services\Feasibility;

use App\Models\FeasibilityApplication;

class StaffingCalculationService
{
    /**
     * Calculate annual cost for a staffing item:
     * (additional_qty * monthly_salary * 12)
     */
    public function calculateItemCost(int $requiredQty, int $existingQty, float $monthlySalary): array
    {
        $additionalQty = max(0, $requiredQty - $existingQty);
        $annualCost = $additionalQty * $monthlySalary * 12;

        return [
            'additional_qty' => $additionalQty,
            'annual_cost' => $annualCost,
        ];
    }

    /**
     * Compute total staffing figures for an application.
     */
    public function getStaffingSummary(FeasibilityApplication $application): array
    {
        $assessments = $application->staffingAssessments;

        $totalRequired = (int) $assessments->sum('required_qty');
        $totalExisting = (int) $assessments->sum('existing_qty');
        $totalAdditional = (int) $assessments->sum('additional_qty');
        $totalMonthlyPayroll = (float) $assessments->sum(fn ($item) => $item->additional_qty * $item->monthly_salary);
        $totalAnnualHRCost = (float) $assessments->sum('annual_cost');

        return [
            'total_required' => $totalRequired,
            'total_existing' => $totalExisting,
            'total_additional' => $totalAdditional,
            'monthly_payroll' => $totalMonthlyPayroll,
            'annual_hr_cost' => $totalAnnualHRCost,
        ];
    }
}
