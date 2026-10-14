<?php

namespace App\Services\Feasibility;

use App\Enums\EvaluationResult;
use App\Models\EvaluationCriterion;
use App\Models\EvaluationScore;
use App\Models\FeasibilityApplication;
use App\Models\Setting;

class FeasibilityScoringService
{
    /**
     * Compute total feasibility score across all criteria using configured weights and subcriteria scores.
     * Weighted Score = (Criterion Obtained Score / Criterion Max Score) * Criterion Weight Percentage
     */
    public function calculateOverallScore(FeasibilityApplication $application): array
    {
        $criteria = EvaluationCriterion::with('subcriteria')->orderBy('sort_order')->get();
        $scores = EvaluationScore::where('application_id', $application->id)->get()->keyBy('subcriterion_id');

        $criteriaBreakdown = [];
        $totalWeightedScore = 0.00;
        $totalWeightConfigured = 0.00;

        foreach ($criteria as $criterion) {
            $criterionMax = 0.00;
            $criterionObtained = 0.00;

            foreach ($criterion->subcriteria as $sub) {
                $criterionMax += (float) $sub->max_score;
                if ($scores->has($sub->id)) {
                    $criterionObtained += (float) $scores->get($sub->id)->score_obtained;
                }
            }

            $weight = (float) $criterion->weight_percentage;
            $totalWeightConfigured += $weight;

            $criterionPercent = $criterionMax > 0 ? ($criterionObtained / $criterionMax) * 100 : 0;
            $weightedContribution = ($criterionPercent * $weight) / 100;
            $totalWeightedScore += $weightedContribution;

            $criteriaBreakdown[] = [
                'criterion_id' => $criterion->id,
                'name' => $criterion->name,
                'weight_percentage' => $weight,
                'max_score' => $criterionMax,
                'score_obtained' => round($criterionObtained, 2),
                'percentage' => round($criterionPercent, 1),
                'weighted_score' => round($weightedContribution, 2),
            ];
        }

        // Normalize if weights do not sum up exactly to 100
        $normalizedScore = $totalWeightConfigured > 0
            ? round(($totalWeightedScore / $totalWeightConfigured) * 100, 2)
            : round($totalWeightedScore, 2);

        $normalizedScore = min(100, max(0, $normalizedScore));
        $rating = $this->determineRating($normalizedScore);

        // Update application
        $application->overall_score = $normalizedScore;
        $application->feasibility_rating = $rating->value;
        $application->save();

        return [
            'overall_score' => $normalizedScore,
            'rating' => $rating,
            'breakdown' => $criteriaBreakdown,
            'total_weight' => $totalWeightConfigured,
        ];
    }

    /**
     * Determine rating based on configurable thresholds.
     */
    public function determineRating(float $score): EvaluationResult
    {
        $highlyThreshold = (float) Setting::get('score_threshold_highly_feasible', 80.0);
        $feasibleThreshold = (float) Setting::get('score_threshold_feasible', 65.0);
        $conditionalThreshold = (float) Setting::get('score_threshold_conditional', 50.0);

        return match (true) {
            $score >= $highlyThreshold => EvaluationResult::HIGHLY_FEASIBLE,
            $score >= $feasibleThreshold => EvaluationResult::FEASIBLE,
            $score >= $conditionalThreshold => EvaluationResult::CONDITIONALLY_FEASIBLE,
            default => EvaluationResult::NOT_FEASIBLE,
        };
    }
}
