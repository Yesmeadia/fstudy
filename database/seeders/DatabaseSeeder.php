<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SystemSettingsSeeder::class,
            DocumentRequirementsSeeder::class,
            EvaluationCriteriaSeeder::class,
            InfrastructureTemplateSeeder::class,
            RiskCategoriesSeeder::class,
            StaffingPositionsSeeder::class,
            SuperAdminSeeder::class,
        ]);
    }
}
