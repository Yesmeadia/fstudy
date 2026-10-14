<?php

namespace Tests\Unit\Feasibility;

use App\Services\Feasibility\DemographicCalculationService;
use PHPUnit\Framework\TestCase;

class DemographicCalculationServiceTest extends TestCase
{
    protected DemographicCalculationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DemographicCalculationService;
    }

    public function test_it_calculates_potential_students_accurately(): void
    {
        // 3500 + 6800 + 6100 + 5400 = 21,800 school-age children.
        // 5.5% capture rate = 1199 students.
        $result = $this->service->calculatePotentialStudents(3500, 6800, 6100, 5400, 5.5);

        $this->assertEquals(1199, $result);
    }

    public function test_it_handles_zero_population_safely(): void
    {
        $result = $this->service->calculatePotentialStudents(0, 0, 0, 0, 10.0);
        $this->assertEquals(0, $result);
    }

    public function test_it_calculates_effective_catchment_population(): void
    {
        // pop1km = 10,000; pop3km = 30,000; pop5km = 60,000
        // primary = 10000, secondary = 20000 * 0.6 = 12000, tertiary = 30000 * 0.3 = 9000
        // total = 10000 + 12000 + 9000 = 31000
        $result = $this->service->calculateEffectiveCatchmentPopulation(10000, 30000, 60000);
        $this->assertEquals(31000, $result);
    }
}
