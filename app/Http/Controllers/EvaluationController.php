<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvaluationScoreRequest;
use App\Models\AuditLog;
use App\Models\EvaluationScore;
use App\Models\EvaluationSubcriterion;
use App\Models\FeasibilityApplication;
use App\Services\Feasibility\FeasibilityScoringService;

class EvaluationController extends Controller
{
    public function __construct(
        protected FeasibilityScoringService $scoringService,
    ) {}

    public function storeScores(StoreEvaluationScoreRequest $request, FeasibilityApplication $application)
    {
        $user = $request->user();
        $scores = $request->input('scores', []);

        foreach ($scores as $item) {
            $sub = EvaluationSubcriterion::findOrFail($item['subcriterion_id']);
            $scoreVal = min((float) $sub->max_score, max(0, (float) $item['score']));

            EvaluationScore::updateOrCreate(
                [
                    'application_id' => $application->id,
                    'subcriterion_id' => $sub->id,
                ],
                [
                    'score_obtained' => $scoreVal,
                    'evaluator_id' => $user->id,
                    'remarks' => $item['remarks'] ?? null,
                ]
            );
        }

        // Recompute weighted score
        $result = $this->scoringService->calculateOverallScore($application);

        AuditLog::log(
            action: 'evaluation_scores_updated',
            model: $application,
            newValues: ['score' => $result['overall_score'], 'rating' => $result['rating']->value]
        );

        return back()->with('success', "Evaluation scores recorded. Overall Feasibility Score updated to {$result['overall_score']}% ({$result['rating']->label()}).");
    }
}
