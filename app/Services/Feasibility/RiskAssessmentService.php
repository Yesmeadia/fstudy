<?php

namespace App\Services\Feasibility;

use App\Enums\RiskLevel;
use App\Models\FeasibilityApplication;
use App\Models\Risk;

class RiskAssessmentService
{
    /**
     * Compute risk score and level from probability and impact.
     */
    public function computeRiskMetrics(int $probability, int $impact): array
    {
        $probability = max(1, min(5, $probability));
        $impact = max(1, min(5, $impact));
        $score = $probability * $impact;
        $level = Risk::calculateLevel($score);

        return [
            'probability' => $probability,
            'impact' => $impact,
            'risk_score' => $score,
            'risk_level' => $level,
        ];
    }

    /**
     * Get aggregated risk summary for an application.
     */
    public function getRiskSummary(FeasibilityApplication $application): array
    {
        $risks = $application->risks;

        $totalRisks = $risks->count();
        $criticalCount = $risks->where('risk_level', RiskLevel::CRITICAL)->count();
        $highCount = $risks->where('risk_level', RiskLevel::HIGH)->count();
        $mediumCount = $risks->where('risk_level', RiskLevel::MEDIUM)->count();
        $lowCount = $risks->where('risk_level', RiskLevel::LOW)->count();

        $averageRiskScore = $totalRisks > 0 ? round($risks->avg('risk_score'), 1) : 0;

        $overallProjectRisk = match (true) {
            $criticalCount > 0 || $averageRiskScore >= 15 => RiskLevel::CRITICAL,
            $highCount >= 2 || $averageRiskScore >= 10 => RiskLevel::HIGH,
            $mediumCount >= 2 || $averageRiskScore >= 5 => RiskLevel::MEDIUM,
            default => RiskLevel::LOW,
        };

        return [
            'total_risks' => $totalRisks,
            'critical_count' => $criticalCount,
            'high_count' => $highCount,
            'medium_count' => $mediumCount,
            'low_count' => $lowCount,
            'average_risk_score' => $averageRiskScore,
            'overall_risk_level' => $overallProjectRisk,
        ];
    }
}
