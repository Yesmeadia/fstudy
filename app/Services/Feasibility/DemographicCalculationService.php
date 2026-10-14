<?php

namespace App\Services\Feasibility;

use App\Models\DemographicAssessment;

class DemographicCalculationService
{
    /**
     * Calculate potential student enrollment based on school-age children and expected capture rate.
     */
    public function calculatePotentialStudents(
        int $childrenAge3to5,
        int $childrenAge6to10,
        int $childrenAge11to14,
        int $childrenAge15to18,
        float $expectedCaptureRate
    ): int {
        $totalSchoolAgeChildren = $childrenAge3to5 + $childrenAge6to10 + $childrenAge11to14 + $childrenAge15to18;
        $captureRateFraction = max(0, min(100, $expectedCaptureRate)) / 100;

        return (int) round($totalSchoolAgeChildren * $captureRateFraction);
    }

    /**
     * Calculate estimated total population in catchment area (weighted by distance).
     * 1km weight = 1.0, 3km incremental weight = 0.6, 5km incremental weight = 0.3
     */
    public function calculateEffectiveCatchmentPopulation(int $pop1km, int $pop3km, int $pop5km): int
    {
        $primary = $pop1km;
        $secondary = max(0, $pop3km - $pop1km);
        $tertiary = max(0, $pop5km - $pop3km);

        return (int) round(($primary * 1.0) + ($secondary * 0.6) + ($tertiary * 0.3));
    }

    /**
     * Apply demographic calculation and update the assessment record.
     */
    public function updateAssessmentPotential(DemographicAssessment $assessment): DemographicAssessment
    {
        $potential = $this->calculatePotentialStudents(
            (int) $assessment->children_age_3_5,
            (int) $assessment->children_age_6_10,
            (int) $assessment->children_age_11_14,
            (int) $assessment->children_age_15_18,
            (float) $assessment->expected_capture_rate
        );

        $assessment->potential_students_calculated = $potential;
        $assessment->save();

        return $assessment;
    }
}
