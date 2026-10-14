<?php

namespace Tests\Unit\Feasibility;

use App\Enums\RiskLevel;
use App\Services\Feasibility\RiskAssessmentService;
use PHPUnit\Framework\TestCase;

class RiskAssessmentServiceTest extends TestCase
{
    protected RiskAssessmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RiskAssessmentService;
    }

    public function test_it_computes_risk_score_and_levels(): void
    {
        // 4 * 4 = 16 -> CRITICAL
        $critical = $this->service->computeRiskMetrics(4, 4);
        $this->assertEquals(16, $critical['risk_score']);
        $this->assertEquals(RiskLevel::CRITICAL, $critical['risk_level']);

        // 3 * 4 = 12 -> HIGH
        $high = $this->service->computeRiskMetrics(3, 4);
        $this->assertEquals(12, $high['risk_score']);
        $this->assertEquals(RiskLevel::HIGH, $high['risk_level']);

        // 2 * 3 = 6 -> MEDIUM
        $medium = $this->service->computeRiskMetrics(2, 3);
        $this->assertEquals(6, $medium['risk_score']);
        $this->assertEquals(RiskLevel::MEDIUM, $medium['risk_level']);

        // 1 * 2 = 2 -> LOW
        $low = $this->service->computeRiskMetrics(1, 2);
        $this->assertEquals(2, $low['risk_score']);
        $this->assertEquals(RiskLevel::LOW, $low['risk_level']);
    }
}
