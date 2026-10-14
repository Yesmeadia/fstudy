<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRiskRequest;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\Risk;
use App\Services\Feasibility\RiskAssessmentService;

class RiskController extends Controller
{
    public function __construct(
        protected RiskAssessmentService $riskService,
    ) {}

    public function store(StoreRiskRequest $request, FeasibilityApplication $application)
    {
        $data = $request->validated();
        $metrics = $this->riskService->computeRiskMetrics((int) $data['probability'], (int) $data['impact']);

        $risk = Risk::create([
            'application_id' => $application->id,
            'category_id' => $data['category_id'],
            'risk_description' => $data['risk_description'],
            'probability' => $metrics['probability'],
            'impact' => $metrics['impact'],
            'risk_score' => $metrics['risk_score'],
            'risk_level' => $metrics['risk_level'],
            'mitigation_strategy' => $data['mitigation_strategy'],
            'responsible_person' => $data['responsible_person'] ?? null,
            'status' => $data['status'],
        ]);

        AuditLog::log('risk_added', $risk, null, ['description' => $risk->risk_description, 'score' => $risk->risk_score]);

        return back()->with('success', 'Risk assessment item recorded successfully.');
    }

    public function destroy(Risk $risk)
    {
        $user = auth()->user();
        if (! $user->isReviewer() && ! $user->isAdmin()) {
            abort(403);
        }

        AuditLog::log('risk_deleted', $risk, ['description' => $risk->risk_description], null);
        $risk->delete();

        return back()->with('success', 'Risk item removed.');
    }
}
