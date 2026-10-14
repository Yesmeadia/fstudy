<?php

namespace Database\Seeders;

use App\Models\StaffingPosition;
use Illuminate\Database\Seeder;

class StaffingPositionsSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['title' => 'Principal / Head of School', 'category' => 'teaching', 'salary' => 95000, 'order' => 1],
            ['title' => 'Vice Principal / Academic Coordinator', 'category' => 'teaching', 'salary' => 70000, 'order' => 2],
            ['title' => 'Post Graduate Teacher (PGT - Class 11-12)', 'category' => 'teaching', 'salary' => 52000, 'order' => 3],
            ['title' => 'Trained Graduate Teacher (TGT - Class 6-10)', 'category' => 'teaching', 'salary' => 42000, 'order' => 4],
            ['title' => 'Primary Teacher (PRT - Class 1-5)', 'category' => 'teaching', 'salary' => 34000, 'order' => 5],
            ['title' => 'Kindergarten / Early Childhood Educator', 'category' => 'teaching', 'salary' => 28000, 'order' => 6],
            ['title' => 'Special Educator / Student Counselor', 'category' => 'teaching', 'salary' => 38000, 'order' => 7],
            ['title' => 'Chief Accountant / Finance Officer', 'category' => 'administrative', 'salary' => 45000, 'order' => 8],
            ['title' => 'Senior Librarian', 'category' => 'administrative', 'salary' => 35000, 'order' => 9],
            ['title' => 'Laboratory Assistants (Physics/Chem/Bio/CS)', 'category' => 'administrative', 'salary' => 25000, 'order' => 10],
            ['title' => 'Office Superintendent & Admin Clerks', 'category' => 'administrative', 'salary' => 28000, 'order' => 11],
            ['title' => 'IT Systems & Network Administrator', 'category' => 'administrative', 'salary' => 36000, 'order' => 12],
            ['title' => 'Transport Supervisor & Fleet Drivers', 'category' => 'support', 'salary' => 22000, 'order' => 13],
            ['title' => 'Security Incharge & Security Guards', 'category' => 'support', 'salary' => 18000, 'order' => 14],
            ['title' => 'Housekeeping & Sanitation Staff', 'category' => 'support', 'salary' => 16000, 'order' => 15],
        ];

        foreach ($positions as $pos) {
            StaffingPosition::updateOrCreate(
                ['title' => $pos['title']],
                [
                    'category' => $pos['category'],
                    'default_monthly_salary' => $pos['salary'],
                    'sort_order' => $pos['order'],
                ]
            );
        }
    }
}
