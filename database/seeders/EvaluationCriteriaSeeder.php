<?php

namespace Database\Seeders;

use App\Models\EvaluationCriterion;
use App\Models\EvaluationSubcriterion;
use Illuminate\Database\Seeder;

class EvaluationCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            [
                'name' => 'Location & Site Suitability',
                'weight' => 15.00,
                'order' => 1,
                'subcriteria' => [
                    ['name' => 'Road connectivity & accessibility from major arterials', 'max' => 20, 'order' => 1],
                    ['name' => 'Public transportation availability and safe transit corridors', 'max' => 15, 'order' => 2],
                    ['name' => 'Land title clarity, freehold/lease tenure and encumbrance freedom', 'max' => 25, 'order' => 3],
                    ['name' => 'Surrounding environmental hygiene and absence of industrial hazards', 'max' => 20, 'order' => 4],
                    ['name' => 'Adequacy of land area conforming to Board norms', 'max' => 20, 'order' => 5],
                ],
            ],
            [
                'name' => 'Catchment Demographic Dynamics',
                'weight' => 20.00,
                'order' => 2,
                'subcriteria' => [
                    ['name' => 'Population density within 1 km, 3 km, and 5 km radius', 'max' => 25, 'order' => 1],
                    ['name' => 'Proportion and concentration of school-age children (3–18 yrs)', 'max' => 25, 'order' => 2],
                    ['name' => 'Household income distribution and paying capacity for education', 'max' => 25, 'order' => 3],
                    ['name' => 'Residential expansion, urbanization pace, and demographic growth trends', 'max' => 25, 'order' => 4],
                ],
            ],
            [
                'name' => 'Student Enrollment Potential',
                'weight' => 20.00,
                'order' => 3,
                'subcriteria' => [
                    ['name' => 'Realistic capture rate and enrollment pipeline across grade levels', 'max' => 35, 'order' => 1],
                    ['name' => '5-Year growth forecast and grade-progression sustainability', 'max' => 35, 'order' => 2],
                    ['name' => 'Early childhood & kindergarten intake volume and feeder strength', 'max' => 30, 'order' => 3],
                ],
            ],
            [
                'name' => 'Physical & Digital Infrastructure',
                'weight' => 15.00,
                'order' => 4,
                'subcriteria' => [
                    ['name' => 'Classroom spatial dimensions, ventilation, and daylight standards', 'max' => 25, 'order' => 1],
                    ['name' => 'Science laboratories, ICT lab, and digital library readiness', 'max' => 25, 'order' => 2],
                    ['name' => 'Playground, athletic field area, and outdoor recreation', 'max' => 25, 'order' => 3],
                    ['name' => 'Fire safety, emergency evacuation, boundary wall, and CCTV coverage', 'max' => 25, 'order' => 4],
                ],
            ],
            [
                'name' => 'Competitive Landscape Analysis',
                'weight' => 10.00,
                'order' => 5,
                'subcriteria' => [
                    ['name' => 'Demand-supply deficit / unmet educational capacity in catchment', 'max' => 40, 'order' => 1],
                    ['name' => 'Value proposition, pedagogy, and fee competitiveness vs nearby schools', 'max' => 35, 'order' => 2],
                    ['name' => 'Differentiation in medium of instruction, co-curriculars, and transport', 'max' => 25, 'order' => 3],
                ],
            ],
            [
                'name' => 'Financial Feasibility & Sustainability',
                'weight' => 15.00,
                'order' => 6,
                'subcriteria' => [
                    ['name' => 'Capital expenditure realism, funding clarity, and promoter equity', 'max' => 25, 'order' => 1],
                    ['name' => 'Operating margin, EBITDA generation, and positive gross surplus', 'max' => 25, 'order' => 2],
                    ['name' => 'Break-even student threshold vs Year 1 & 2 projected enrollments', 'max' => 25, 'order' => 3],
                    ['name' => 'Return on Investment (ROI) and reasonable payback period (5–8 yrs)', 'max' => 25, 'order' => 4],
                ],
            ],
            [
                'name' => 'Management & Institutional Capability',
                'weight' => 5.00,
                'order' => 7,
                'subcriteria' => [
                    ['name' => 'Trust / Society track record in educational or social administration', 'max' => 40, 'order' => 1],
                    ['name' => 'Faculty recruitment strategy, teacher-student ratio, and retention plan', 'max' => 35, 'order' => 2],
                    ['name' => 'Regulatory compliance governance, statutory filings, and audit rigor', 'max' => 25, 'order' => 3],
                ],
            ],
        ];

        foreach ($criteria as $cData) {
            $criterion = EvaluationCriterion::updateOrCreate(
                ['name' => $cData['name']],
                [
                    'weight_percentage' => $cData['weight'],
                    'max_score' => 100.00,
                    'sort_order' => $cData['order'],
                ]
            );

            foreach ($cData['subcriteria'] as $sub) {
                EvaluationSubcriterion::updateOrCreate(
                    [
                        'criterion_id' => $criterion->id,
                        'name' => $sub['name'],
                    ],
                    [
                        'max_score' => $sub['max'],
                        'sort_order' => $sub['order'],
                    ]
                );
            }
        }
    }
}
