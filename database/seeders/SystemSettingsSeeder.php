<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'org_name', 'value' => 'YES INDIA FOUNDATION', 'group' => 'organization', 'type' => 'string', 'description' => 'Official Organization / Board Name'],
            ['key' => 'org_address', 'value' => 'RKP.V/720-A 12, Hanna Tower, Opp. BUS Stand Ramanatukara, Kozhikode -673633, Kerala', 'group' => 'organization', 'type' => 'string', 'description' => 'Headquarter Address'],
            ['key' => 'org_contact', 'value' => '+91  989 566 2111 | info@yesindiafoundation.com | www.yesindiafoundation.com', 'group' => 'organization', 'type' => 'string', 'description' => 'Contact Phone & Email'],
            ['key' => 'application_prefix', 'value' => 'FS-2026', 'group' => 'general', 'type' => 'string', 'description' => 'Feasibility Application Number Prefix'],
            ['key' => 'academic_year', 'value' => '2026-2027', 'group' => 'general', 'type' => 'string', 'description' => 'Current Academic Session'],
            ['key' => 'score_threshold_highly_feasible', 'value' => '80.0', 'group' => 'scoring', 'type' => 'decimal', 'description' => 'Minimum score % for Highly Feasible rating'],
            ['key' => 'score_threshold_feasible', 'value' => '65.0', 'group' => 'scoring', 'type' => 'decimal', 'description' => 'Minimum score % for Feasible rating'],
            ['key' => 'score_threshold_conditional', 'value' => '50.0', 'group' => 'scoring', 'type' => 'decimal', 'description' => 'Minimum score % for Conditionally Feasible rating'],
            ['key' => 'document_max_size_kb', 'value' => '10240', 'group' => 'documents', 'type' => 'integer', 'description' => 'Max file upload size in KB (10MB)'],
            ['key' => 'allowed_document_types', 'value' => 'pdf,jpg,jpeg,png,docx,xlsx', 'group' => 'documents', 'type' => 'string', 'description' => 'Allowed upload file extensions'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
