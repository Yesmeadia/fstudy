<?php

namespace Database\Seeders;

use App\Models\RiskCategory;
use Illuminate\Database\Seeder;

class RiskCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Financial Risk', 'slug' => 'financial'],
            ['name' => 'Location & Access Risk', 'slug' => 'location'],
            ['name' => 'Competition & Market Saturation Risk', 'slug' => 'competition'],
            ['name' => 'Student Demand & Enrollment Risk', 'slug' => 'demand'],
            ['name' => 'Infrastructure & Land Risk', 'slug' => 'infrastructure'],
            ['name' => 'Staffing & Faculty Retention Risk', 'slug' => 'staffing'],
            ['name' => 'Regulatory & Affiliation Compliance Risk', 'slug' => 'regulatory'],
            ['name' => 'Management & Governance Risk', 'slug' => 'management'],
        ];

        foreach ($categories as $cat) {
            RiskCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
