<?php

namespace Database\Seeders;

use App\Models\InfrastructureCategory;
use App\Models\InfrastructureItem;
use Illuminate\Database\Seeder;

class InfrastructureTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Academic Infrastructure',
                'slug' => 'academic',
                'sort_order' => 1,
                'icon' => 'academic-cap',
                'items' => [
                    ['name' => 'Adequate Classrooms (min 400 sq.ft each)', 'desc' => 'Well-ventilated with natural lighting and acoustics', 'mandatory' => true],
                    ['name' => 'Composite Science Laboratory', 'desc' => 'Equipped for Physics, Chemistry & Biology experiments', 'mandatory' => true],
                    ['name' => 'Computer Science & AI Lab', 'desc' => 'Minimum 30 high-speed networked terminals with UPS backup', 'mandatory' => true],
                    ['name' => 'School Central Library & Reading Room', 'desc' => 'Minimum 2,500 curated books and digital subscriptions', 'mandatory' => true],
                    ['name' => 'Smart Interactive Classrooms', 'desc' => 'Interactive digital flat panels and high-speed Wi-Fi', 'mandatory' => false],
                    ['name' => 'Art, Craft & Music Activity Room', 'desc' => 'Dedicated creative studio and instrument area', 'mandatory' => false],
                ],
            ],
            [
                'name' => 'Administrative Infrastructure',
                'slug' => 'administration',
                'sort_order' => 2,
                'icon' => 'office-building',
                'items' => [
                    ['name' => 'Principal / Head of Institution Office', 'desc' => 'Dedicated executive cabin with meeting space', 'mandatory' => true],
                    ['name' => 'Administrative & Accounts Office', 'desc' => 'Fee counter, server console, and records filing', 'mandatory' => true],
                    ['name' => 'Staff Common Room with Workstations', 'desc' => 'Individual lockers, pantry space, and prep tables', 'mandatory' => true],
                    ['name' => 'Conference & Management Board Room', 'desc' => 'Audio-visual conference facility for faculty & trustees', 'mandatory' => false],
                    ['name' => 'Visitor Reception & Parent Lounge', 'desc' => 'Clean welcoming reception area with visitor register', 'mandatory' => true],
                ],
            ],
            [
                'name' => 'Student Amenities & Health Facilities',
                'slug' => 'student_facilities',
                'sort_order' => 3,
                'icon' => 'user-group',
                'items' => [
                    ['name' => 'Gender-Segregated Restrooms & Toilets', 'desc' => 'Clean ratio of 1 toilet per 30 students + accessible stall', 'mandatory' => true],
                    ['name' => 'Hygienic RO Purified Drinking Water Stations', 'desc' => 'Tested potable water points on every floor', 'mandatory' => true],
                    ['name' => 'Full-size Sports Playground & Athletic Track', 'desc' => 'Dedicated outdoor sports facility (football, cricket, athletics)', 'mandatory' => true],
                    ['name' => 'School Transport Fleet & Bus Parking Bay', 'desc' => 'School-owned or contracted buses with GPS and female attendant', 'mandatory' => false],
                    ['name' => 'Cafeteria & Dining Hall', 'desc' => 'Hygienic food preparation & covered eating area', 'mandatory' => false],
                    ['name' => 'Medical Infirmary / First-Aid Room', 'desc' => 'Dedicated bed, first-aid supplies, and visiting nurse tie-up', 'mandatory' => true],
                ],
            ],
            [
                'name' => 'Safety, Security & Regulatory Compliance',
                'slug' => 'safety',
                'sort_order' => 4,
                'icon' => 'shield-check',
                'items' => [
                    ['name' => 'Fire Safety System & Emergency Exits', 'desc' => 'Extinguishers, hose reels, fire alarms and clear egress staircases', 'mandatory' => true],
                    ['name' => 'Comprehensive CCTV Surveillance', 'desc' => 'Complete coverage of gates, corridors, perimeter and common areas', 'mandatory' => true],
                    ['name' => 'High Masonry Boundary Wall & Guard Posts', 'desc' => 'Minimum 6-foot boundary wall with 24/7 security station', 'mandatory' => true],
                    ['name' => 'Electrical Safety & Silent Power Backup (DG Set)', 'desc' => 'Concealed wiring, circuit breakers, and automatic generator switch', 'mandatory' => true],
                    ['name' => 'Disaster Preparedness & Evacuation Plan', 'desc' => 'Prominently displayed floor-wise evacuation maps', 'mandatory' => true],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = InfrastructureCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'sort_order' => $catData['sort_order'],
                    'icon' => $catData['icon'],
                ]
            );

            $order = 1;
            foreach ($catData['items'] as $itemData) {
                InfrastructureItem::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $itemData['name'],
                    ],
                    [
                        'description' => $itemData['desc'],
                        'is_mandatory' => $itemData['mandatory'],
                        'sort_order' => $order++,
                    ]
                );
            }
        }
    }
}
