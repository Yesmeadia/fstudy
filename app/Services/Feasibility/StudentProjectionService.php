<?php

namespace App\Services\Feasibility;

use App\Models\FeasibilityApplication;

class StudentProjectionService
{
    public const STUDENTS_PER_SECTION = 35;

    public const TEACHER_STUDENT_RATIO = 25; // 1 teacher per 25 students

    /**
     * Get default class structure for a K-12 school.
     */
    public function getDefaultClasses(): array
    {
        return [
            ['name' => 'Pre-KG / Nursery', 'order' => 1],
            ['name' => 'LKG', 'order' => 2],
            ['name' => 'UKG', 'order' => 3],
            ['name' => 'Grade 1', 'order' => 4],
            ['name' => 'Grade 2', 'order' => 5],
            ['name' => 'Grade 3', 'order' => 6],
            ['name' => 'Grade 4', 'order' => 7],
            ['name' => 'Grade 5', 'order' => 8],
            ['name' => 'Grade 6', 'order' => 9],
            ['name' => 'Grade 7', 'order' => 10],
            ['name' => 'Grade 8', 'order' => 11],
            ['name' => 'Grade 9', 'order' => 12],
            ['name' => 'Grade 10', 'order' => 13],
            ['name' => 'Grade 11', 'order' => 14],
            ['name' => 'Grade 12', 'order' => 15],
        ];
    }

    /**
     * Compute section and teacher requirements for a projection.
     */
    public function computeRequirements(int $enrollment, int $maxCapacity = 100): array
    {
        $sections = (int) max(1, ceil($enrollment / self::STUDENTS_PER_SECTION));
        $teachers = (int) max(1, ceil($enrollment / self::TEACHER_STUDENT_RATIO));
        $capacityUtil = $maxCapacity > 0 ? min(100, round(($enrollment / $maxCapacity) * 100, 2)) : 0;

        return [
            'sections_required' => $sections,
            'teachers_required' => $teachers,
            'capacity_utilization_pct' => $capacityUtil,
        ];
    }

    /**
     * Calculate 5-year totals across all grades for an application.
     */
    public function getEnrollmentTotals(FeasibilityApplication $application): array
    {
        $projections = $application->studentProjections;

        $year1 = (int) $projections->sum('year_1_projection');
        $year2 = (int) $projections->sum('year_2_projection');
        $year3 = (int) $projections->sum('year_3_projection');
        $year4 = (int) $projections->sum('year_4_projection');
        $year5 = (int) $projections->sum('year_5_projection');
        $sections = (int) $projections->sum('sections_required');
        $teachers = (int) $projections->sum('teachers_required');

        $growthYear1to5 = $year1 > 0 ? round((($year5 - $year1) / $year1) * 100, 1) : 0;

        return [
            'year_1' => $year1,
            'year_2' => $year2,
            'year_3' => $year3,
            'year_4' => $year4,
            'year_5' => $year5,
            'total_sections' => $sections,
            'total_teachers' => $teachers,
            'five_year_growth_pct' => $growthYear1to5,
        ];
    }
}
