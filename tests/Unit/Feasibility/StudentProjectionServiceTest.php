<?php

namespace Tests\Unit\Feasibility;

use App\Services\Feasibility\StudentProjectionService;
use PHPUnit\Framework\TestCase;

class StudentProjectionServiceTest extends TestCase
{
    protected StudentProjectionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new StudentProjectionService;
    }

    public function test_it_provides_fifteen_default_classes(): void
    {
        $classes = $this->service->getDefaultClasses();
        $this->assertCount(15, $classes);
        $this->assertEquals('Pre-KG / Nursery', $classes[0]['name']);
        $this->assertEquals('Grade 12', $classes[14]['name']);
    }

    public function test_it_computes_section_and_teacher_requirements(): void
    {
        // 70 students -> 2 sections (35 per section), 3 teachers (25 per teacher)
        $reqs = $this->service->computeRequirements(70, 100);

        $this->assertEquals(2, $reqs['sections_required']);
        $this->assertEquals(3, $reqs['teachers_required']);
        $this->assertEquals(70.0, $reqs['capacity_utilization_pct']);
    }
}
