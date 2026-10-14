<?php

namespace Database\Seeders;

use App\Models\DocumentRequirement;
use Illuminate\Database\Seeder;

class DocumentRequirementsSeeder extends Seeder
{
    public function run(): void
    {
        $requirements = [
            [
                'title' => 'Land Title Deed / Registered 30-Year Lease Agreement',
                'description' => 'Clear title deed or registered lease in the name of the Trust/Society for the school site.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf,docx',
                'max_size_kb' => 10240,
                'sort_order' => 1,
            ],
            [
                'title' => 'Trust / Society / Section 8 Company Registration Certificate',
                'description' => 'Certificate of incorporation/registration along with Memorandum of Association & bylaws.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf',
                'max_size_kb' => 5120,
                'sort_order' => 2,
            ],
            [
                'title' => 'Approved Master Architectural & Structural Building Plan',
                'description' => 'Municipal or Gram Panchayat approved building layout with classroom dimensions.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf',
                'max_size_kb' => 10240,
                'sort_order' => 3,
            ],
            [
                'title' => 'Fire Safety Certificate / No Objection Certificate (NOC)',
                'description' => 'Official NOC issued by the State Fire Services Department.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf,jpg,jpeg,png',
                'max_size_kb' => 5120,
                'sort_order' => 4,
            ],
            [
                'title' => 'Building Structural Stability & Safety Certificate',
                'description' => 'Certified report from an authorized Structural Engineer or PWD officer.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf',
                'max_size_kb' => 5120,
                'sort_order' => 5,
            ],
            [
                'title' => 'Audited Balance Sheets / 3-Year Financial Statements',
                'description' => 'Chartered Accountant audited balance sheets and bank solvency certificate.',
                'is_mandatory' => false,
                'allowed_extensions' => 'pdf,xlsx',
                'max_size_kb' => 10240,
                'sort_order' => 6,
            ],
            [
                'title' => 'Site & Infrastructure Photographic Proof',
                'description' => 'High-resolution photographs of front facade, classrooms, laboratories, and playground.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf,jpg,jpeg,png',
                'max_size_kb' => 10240,
                'sort_order' => 7,
            ],
            [
                'title' => 'Safe Drinking Water & Sanitary Inspection Certificate',
                'description' => 'Potability certificate from public health laboratory and hygiene clearance.',
                'is_mandatory' => true,
                'allowed_extensions' => 'pdf',
                'max_size_kb' => 5120,
                'sort_order' => 8,
            ],
        ];

        foreach ($requirements as $req) {
            DocumentRequirement::updateOrCreate(
                ['title' => $req['title']],
                $req
            );
        }
    }
}
