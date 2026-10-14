<?php

namespace App\Models;

use App\Casts\SafeEncryptedArray;
use App\Casts\SafeEncryptedString;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

/**
 * @property int $id
 * @property int $application_id
 * @property string|null $institution_name
 * @property string|null $location
 * @property string|null $present_management
 * @property string|null $inspection_dates
 * @property string|null $inspection_team
 * @property array<int, array<string, mixed>>|null $section1_legal
 * @property array<int, array<string, mixed>>|null $section2_infrastructure
 * @property array<int, array<string, mixed>>|null $section3_rooms
 * @property array<int, array<string, mixed>>|null $section4_students
 * @property array<int, array<string, mixed>>|null $section5_enrolment
 * @property array<int, array<string, mixed>>|null $section6_academic
 * @property array<int, array<string, mixed>>|null $section7_staff
 * @property array<int, array<string, mixed>>|null $section8_financial
 * @property array<int, array<string, mixed>>|null $section9_brand
 * @property array<int, array<string, mixed>>|null $section10_primary_assessment
 * @property array<int, array<string, mixed>>|null $section11_secondary_assessment
 * @property array<int, array<string, mixed>>|null $section12_teachers_assessment
 * @property array<string, mixed>|null $section13_final_remarks
 * @property string|null $overall_summary
 * @property string|null $overall_recommendation
 * @property string|null $inspector_name
 * @property Carbon|null $signed_at
 */
class FeasibilityChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'institution_name',
        'location',
        'present_management',
        'inspection_dates',
        'inspection_team',
        'section1_legal',
        'section2_infrastructure',
        'section3_rooms',
        'section4_students',
        'section5_enrolment',
        'section6_academic',
        'section7_staff',
        'section8_financial',
        'section9_brand',
        'section10_primary_assessment',
        'section11_secondary_assessment',
        'section12_teachers_assessment',
        'section13_final_remarks',
        'overall_summary',
        'overall_recommendation',
        'inspector_name',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'section1_legal' => SafeEncryptedArray::class,
            'section2_infrastructure' => 'array',
            'section3_rooms' => 'array',
            'section4_students' => 'array',
            'section5_enrolment' => 'array',
            'section6_academic' => 'array',
            'section7_staff' => SafeEncryptedArray::class,
            'section8_financial' => SafeEncryptedArray::class,
            'section9_brand' => 'array',
            'section10_primary_assessment' => 'array',
            'section11_secondary_assessment' => 'array',
            'section12_teachers_assessment' => 'array',
            'section13_final_remarks' => SafeEncryptedArray::class,
            'overall_summary' => SafeEncryptedString::class,
            'signed_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(FeasibilityApplication::class, 'application_id');
    }

    /**
     * Default Template Questions matching the official YES India Foundation checklist.
     */
    public static function defaultTemplate(): array
    {
        return [
            'section1_legal' => [
                [
                    'no' => 1,
                    'point' => 'Legal name of the institution and operating entity verified from original registration/constitutional documents.',
                    'compliance' => null,
                    'remarks' => '',
                ],
                [
                    'no' => 2,
                    'point' => 'Ownership of school land/buildings and the legal right of the present management to operate the institution established.',
                    'compliance' => null,
                    'remarks' => '',
                ],
                [
                    'no' => 3,
                    'point' => 'Any lease, licence, management agreement, franchise, affiliation agreement or other right-of-use document examined for tenure and termination clauses.',
                    'compliance' => null,
                    'remarks' => '',
                ],
                [
                    'no' => 4,
                    'point' => 'Current school recognition certificate/order verified, including validity period and conditions.',
                    'compliance' => null,
                    'remarks' => '',
                ],
                [
                    'no' => 5,
                    'point' => 'Board affiliation/recognition verified (as applicable: State Board/CBSE/ICSE/other).',
                    'compliance' => null,
                    'remarks' => '',
                ],
                [
                    'no' => 6,
                    'point' => 'Affiliation code/UDISE or equivalent institutional identifiers verified.',
                    'compliance' => null,
                    'remarks' => '',
                ],
                [
                    'no' => 7,
                    'point' => 'Permitted classes/sections and approved student strength verified.',
                    'compliance' => null,
                    'remarks' => '',
                ],
            ],

            'section2_infrastructure' => [
                ['no' => 1, 'point' => 'Rooms details', 'compliance' => null, 'remarks' => ''],
                ['no' => 2, 'point' => 'Open space /verandah in buildings', 'compliance' => null, 'remarks' => ''],
                ['no' => 3, 'point' => 'Ground & land details', 'compliance' => null, 'remarks' => ''],
                ['no' => 4, 'point' => "Furniture's status and condition", 'compliance' => null, 'remarks' => ''],
                ['no' => 5, 'point' => 'Teaching aids & tools', 'compliance' => null, 'remarks' => ''],
                ['no' => 6, 'point' => 'Laboratories: science/computer/language/other labs, equipment, safety and usability.', 'compliance' => null, 'remarks' => ''],
                ['no' => 7, 'point' => 'Library: collection, cataloguing, seating, digital resources and usage.', 'compliance' => null, 'remarks' => ''],
                ['no' => 8, 'point' => 'Smart classrooms/projectors/displays/audio systems inventoried and tested.', 'compliance' => null, 'remarks' => ''],
                ['no' => 9, 'point' => 'Computer/network/Internet infrastructure tested.', 'compliance' => null, 'remarks' => ''],
                ['no' => 10, 'point' => 'Enterprise Resource Planning (ERP) / Learning Management System (LMS)', 'compliance' => null, 'remarks' => ''],
                ['no' => 11, 'point' => 'Toilets/wash facilities with gender separation', 'compliance' => null, 'remarks' => ''],
                ['no' => 12, 'point' => 'Drinking water facility', 'compliance' => null, 'remarks' => ''],
                ['no' => 13, 'point' => 'Kitchen/canteen facilities', 'compliance' => null, 'remarks' => ''],
                ['no' => 14, 'point' => 'Transport facilities, buses, permits, fitness, insurance, drivers and attendants, profitability checked, if applicable.', 'compliance' => null, 'remarks' => ''],
                ['no' => 15, 'point' => 'School Hostel Facility', 'compliance' => null, 'remarks' => ''],
                ['no' => 16, 'point' => 'Staff hostel facility', 'compliance' => null, 'remarks' => ''],
                ['no' => 17, 'point' => 'Security systems like CCTV', 'compliance' => null, 'remarks' => ''],
            ],

            'section3_rooms' => [
                ['type' => 'Standard Classroom', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
                ['type' => 'Science Laboratory', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
                ['type' => 'Computer Lab', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
                ['type' => 'Administrative / Principal Office', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
                ['type' => 'Staff Room', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
                ['type' => 'Library / Reading Room', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
                ['type' => 'Multipurpose / Activity Hall', 'count' => '', 'size_feet' => '', 'current_use' => '', 'furniture' => '', 'remarks' => ''],
            ],

            'section4_students' => [
                ['no' => 1, 'point' => 'Student count by class, section, gender and category verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 2, 'point' => 'Admission records for current and recent years sampled and verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 3, 'point' => 'Teacher/students ratio', 'compliance' => null, 'remarks' => ''],
            ],

            'section5_enrolment' => [
                ['class_division' => 'Nursery', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'LKG', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'UKG', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 1', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 2', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 3', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 4', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 5', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 6', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 7', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 8', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 9', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 10', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 11', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
                ['class_division' => 'Class 12', 'boys' => '', 'girls' => '', 'total' => '', 'last_year_total' => '', 'trend' => ''],
            ],

            'section6_academic' => [
                ['no' => 1, 'point' => 'Curriculum currently followed and its conformity with board/state requirements verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 2, 'point' => 'Annual academic plan and academic calendar reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 3, 'point' => 'Class-wise timetable, working days and instructional hours reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 4, 'point' => 'Lesson planning, classroom observation and teacher supervision systems reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 5, 'point' => 'Assessment calendar, question-paper process, examination security and result analysis reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 6, 'point' => 'Student learning outcomes/achievement data recording verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 7, 'point' => 'Board examination performance and subject-wise trends analysed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 8, 'point' => 'Remedial/enrichment programmes and support for slow/advanced learners reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 9, 'point' => 'Academic records, report cards, portfolios and student progress documentation verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 10, 'point' => 'Co-curricular, arts, sports, clubs and talent-development programmes reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 11, 'point' => 'Co-curricular performance documentation verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 12, 'point' => 'Books, uniform vendors management verified.', 'compliance' => null, 'remarks' => ''],
            ],

            'section7_staff' => [
                ['no' => 1, 'point' => 'Employee list with role, designation, qualification, temporary/permanent/ experience and salary of every employee verified. (Including whether aided and unaided staff)', 'compliance' => null, 'remarks' => ''],
                ['no' => 2, 'point' => 'Leave records, attendance records and personnel files reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 3, 'point' => 'Staff-Management agreement format.', 'compliance' => null, 'remarks' => ''],
            ],

            'section8_financial' => [
                ['no' => 1, 'point' => 'Audited financial statements for the last year obtained and reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 2, 'point' => 'Monthly management accounts for the latest 12 months obtained.', 'compliance' => null, 'remarks' => ''],
                ['no' => 3, 'point' => 'Fee structure by class/category obtained and approved structure verified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 4, 'point' => 'Actual fee collection reconciled with student registers and bank receipts.', 'compliance' => null, 'remarks' => ''],
                ['no' => 5, 'point' => 'Any promised seats, fee guarantees, informal commitments or special admissions identified.', 'compliance' => null, 'remarks' => ''],
                ['no' => 6, 'point' => 'Fee concessions/scholarships/waivers reconciled to authorised approvals.', 'compliance' => null, 'remarks' => ''],
                ['no' => 7, 'point' => 'Liabilities; salary, building investment, loans, purchase accounts verified.', 'compliance' => null, 'remarks' => ''],
            ],

            'section9_brand' => [
                ['no' => 1, 'point' => 'Relationship with local authorities/community leaders/parents’ groups assessed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 2, 'point' => 'Existing partnerships with NGOs, companies, colleges, clubs or institutions reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 3, 'point' => 'Social media checked; whatsapp community, facebook, Instagram, youtube, LinkedIn, etc surfed and reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 4, 'point' => 'Website and updates reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 5, 'point' => 'Administrational Hierarchy discussed & reviewed.', 'compliance' => null, 'remarks' => ''],
                ['no' => 6, 'point' => 'Meetings & Minutes reviewed.', 'compliance' => null, 'remarks' => ''],
            ],

            'section10_primary_assessment' => [
                'classes' => ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'],
                'rows' => [
                    ['particular' => 'Basic reading', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                    ['particular' => 'Basic writing', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                    ['particular' => 'Basic mathematical operations', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                    ['particular' => 'Can speak confidently', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                    ['particular' => 'Can listen attentively', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ],
            ],

            'section11_secondary_assessment' => [
                ['particular' => 'Fluent reading', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Reading with comprehension', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Can write with correct spelling', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Problem solving ability', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Can explain a concept in their own words', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Can answer why and how question', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Can solve mental mathematics', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
                ['particular' => 'Can participate in group works', 'c1' => '', 'c2' => '', 'c3' => '', 'c4' => '', 'c5' => '', 'remarks' => ''],
            ],

            'section12_teachers_assessment' => [
                'teachers' => ['Teacher 1', 'Teacher 2', 'Teacher 3', 'Teacher 4', 'Teacher 5', 'Teacher 6', 'Teacher 7'],
                'rows' => [
                    ['particular' => 'English proficiency', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ['particular' => 'Subject Quality', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ['particular' => 'Documentation professionalism', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ['particular' => 'Professional exposure', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ['particular' => 'Certified qualities', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ['particular' => 'Uncertified and social qualities', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                    ['particular' => 'Trainings provided in the school', 't1' => '', 't2' => '', 't3' => '', 't4' => '', 't5' => '', 't6' => '', 't7' => '', 'remarks' => ''],
                ],
            ],

            'section13_final_remarks' => [
                'academic' => '',
                'financial' => '',
                'infrastructural' => '',
                'legal' => '',
                'administration_staff' => '',
                'teaching_staff' => '',
                'safety' => '',
                'signed_by' => '',
            ],
        ];
    }

    /**
     * Compute verification compliance summary.
     */
    public function getComplianceSummary(): array
    {
        $yes = 0;
        $no = 0;
        $total = 0;

        $sections = [
            $this->section1_legal ?? [],
            $this->section2_infrastructure ?? [],
            $this->section4_students ?? [],
            $this->section6_academic ?? [],
            $this->section7_staff ?? [],
            $this->section8_financial ?? [],
            $this->section9_brand ?? [],
        ];

        foreach ($sections as $sec) {
            foreach ($sec as $item) {
                $total++;
                if (($item['compliance'] ?? '') === 'yes') {
                    $yes++;
                } elseif (($item['compliance'] ?? '') === 'no') {
                    $no++;
                }
            }
        }

        $percentage = $total > 0 ? round(($yes / $total) * 100, 1) : 0;

        return [
            'yes' => $yes,
            'no' => $no,
            'unanswered' => max(0, $total - ($yes + $no)),
            'total' => $total,
            'percentage' => $percentage,
        ];
    }

    public function getSection10PrimaryAssessmentAttribute($value)
    {
        $defaultTemplate = static::defaultTemplate()['section10_primary_assessment'];
        $decoded = is_string($value) ? json_decode($value, true) : $value;
        if (empty($decoded) || ! is_array($decoded)) {
            return $defaultTemplate;
        }

        // Format 1: Matrix format with 'classes' and 'rows'
        if (isset($decoded['rows']) && is_array($decoded['rows'])) {
            $classes = $decoded['classes'] ?? [];
            if (! is_array($classes)) {
                $classes = [];
            }
            while (count($classes) < 5) {
                $idx = count($classes) + 1;
                $classes[] = "Class {$idx}";
            }
            $classes = array_slice(array_values($classes), 0, 5);

            $rows = [];
            foreach ($decoded['rows'] as $rIdx => $row) {
                $rows[] = [
                    'particular' => $row['particular'] ?? ($defaultTemplate['rows'][$rIdx]['particular'] ?? 'Competency '.($rIdx + 1)),
                    'c1' => $row['c1'] ?? '',
                    'c2' => $row['c2'] ?? '',
                    'c3' => $row['c3'] ?? '',
                    'c4' => $row['c4'] ?? '',
                    'c5' => $row['c5'] ?? '',
                    'remarks' => $row['remarks'] ?? '',
                ];
            }

            return [
                'classes' => $classes,
                'rows' => $rows,
            ];
        }

        // Format 2: Flat list of rows with particulars
        if (isset($decoded[0]['particular'])) {
            $classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
            $rows = [];
            foreach ($decoded as $rIdx => $row) {
                $rows[] = [
                    'particular' => $row['particular'] ?? ($defaultTemplate['rows'][$rIdx]['particular'] ?? 'Competency '.($rIdx + 1)),
                    'c1' => $row['c1'] ?? ($row['marking'] ?? ''),
                    'c2' => $row['c2'] ?? '',
                    'c3' => $row['c3'] ?? '',
                    'c4' => $row['c4'] ?? '',
                    'c5' => $row['c5'] ?? '',
                    'remarks' => $row['remarks'] ?? '',
                ];
            }

            return [
                'classes' => $classes,
                'rows' => $rows,
            ];
        }

        // Format 3: Legacy list of class items: [['class' => 'Class 1', 'marking' => '...', 'remarks' => '...'], ...]
        $classes = [];
        foreach ($decoded as $idx => $item) {
            if (! empty($item['class'])) {
                $classes[] = $item['class'];
            }
        }
        while (count($classes) < 5) {
            $idx = count($classes) + 1;
            $classes[] = "Class {$idx}";
        }
        $classes = array_slice(array_values($classes), 0, 5);

        $defaultRows = $defaultTemplate['rows'];
        if (! empty($decoded[0]['marking'])) {
            $defaultRows[0]['c1'] = $decoded[0]['marking'];
        }
        if (! empty($decoded[0]['remarks'])) {
            $defaultRows[0]['remarks'] = $decoded[0]['remarks'];
        }

        return [
            'classes' => $classes,
            'rows' => $defaultRows,
        ];
    }

    public function getSection11SecondaryAssessmentAttribute($value)
    {
        $default = static::defaultTemplate()['section11_secondary_assessment'];
        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (empty($decoded) || ! is_array($decoded)) {
            return $default;
        }

        $items = isset($decoded['rows']) && is_array($decoded['rows']) ? $decoded['rows'] : $decoded;
        if (! is_array($items)) {
            return $default;
        }

        $result = [];
        foreach ($items as $idx => $item) {
            if (! is_array($item)) {
                continue;
            }
            $result[] = [
                'particular' => $item['particular'] ?? ($default[$idx]['particular'] ?? 'Competency '.($idx + 1)),
                'c1' => $item['c1'] ?? '',
                'c2' => $item['c2'] ?? '',
                'c3' => $item['c3'] ?? '',
                'c4' => $item['c4'] ?? '',
                'c5' => $item['c5'] ?? '',
                'remarks' => $item['remarks'] ?? '',
                'evidence_photo' => $item['evidence_photo'] ?? '',
            ];
        }

        return ! empty($result) ? $result : $default;
    }

    public function getSection12TeachersAssessmentAttribute($value)
    {
        $defaultTemplate = static::defaultTemplate()['section12_teachers_assessment'];
        $decoded = is_string($value) ? json_decode($value, true) : $value;
        if (empty($decoded) || ! is_array($decoded)) {
            return $defaultTemplate;
        }

        // Format 1: Matrix format with 'teachers' and 'rows'
        if (isset($decoded['rows']) && is_array($decoded['rows'])) {
            $teachers = $decoded['teachers'] ?? [];
            if (! is_array($teachers)) {
                $teachers = [];
            }
            while (count($teachers) < 7) {
                $idx = count($teachers) + 1;
                $teachers[] = "Teacher {$idx}";
            }
            $teachers = array_slice(array_values($teachers), 0, 7);

            $rows = [];
            foreach ($decoded['rows'] as $rIdx => $row) {
                $rows[] = [
                    'particular' => $row['particular'] ?? ($defaultTemplate['rows'][$rIdx]['particular'] ?? 'Criterion '.($rIdx + 1)),
                    't1' => $row['t1'] ?? '',
                    't2' => $row['t2'] ?? '',
                    't3' => $row['t3'] ?? '',
                    't4' => $row['t4'] ?? '',
                    't5' => $row['t5'] ?? '',
                    't6' => $row['t6'] ?? '',
                    't7' => $row['t7'] ?? '',
                    'remarks' => $row['remarks'] ?? '',
                ];
            }

            return [
                'teachers' => $teachers,
                'rows' => $rows,
            ];
        }

        // Format 2: Flat list of rows from legacy structure
        $teachers = ['Teacher 1', 'Teacher 2', 'Teacher 3', 'Teacher 4', 'Teacher 5', 'Teacher 6', 'Teacher 7'];
        $rows = [];
        foreach ($decoded as $rIdx => $row) {
            $rows[] = [
                'particular' => $row['particular'] ?? ($defaultTemplate['rows'][$rIdx]['particular'] ?? 'Criterion '.($rIdx + 1)),
                't1' => $row['t1'] ?? '',
                't2' => $row['t2'] ?? '',
                't3' => $row['t3'] ?? '',
                't4' => $row['t4'] ?? '',
                't5' => $row['t5'] ?? '',
                't6' => $row['t6'] ?? '',
                't7' => $row['t7'] ?? '',
                'remarks' => $row['remarks'] ?? '',
            ];
        }

        return [
            'teachers' => $teachers,
            'rows' => $rows,
        ];
    }

    public function getSection13FinalRemarksAttribute($value): array
    {
        $default = static::defaultTemplate()['section13_final_remarks'];

        if ($value === null || $value === '') {
            return $default;
        }

        $decoded = null;

        if (is_array($value)) {
            $decoded = $value;
        } elseif (is_string($value)) {
            // 1. First try to decrypt directly (SQLite or raw ciphertext)
            try {
                $decrypted = Crypt::decryptString($value);
                $parsed = json_decode($decrypted, true);
                if (is_array($parsed)) {
                    $decoded = $parsed;
                }
            } catch (\Throwable $e) {
                // Not a direct ciphertext string
            }

            // 2. Try json_decode in case it was stored wrapped in JSON quotes (MySQL JSON column)
            if ($decoded === null) {
                $json = json_decode($value, true);
                if (is_string($json)) {
                    try {
                        $decrypted = Crypt::decryptString($json);
                        $parsed = json_decode($decrypted, true);
                        if (is_array($parsed)) {
                            $decoded = $parsed;
                        }
                    } catch (\Throwable $e) {
                        // Decryption failed
                    }
                } elseif (is_array($json)) {
                    // Plain unencrypted JSON array
                    $decoded = $json;
                }
            }
        }

        if (! is_array($decoded)) {
            return $default;
        }

        $merged = array_merge($default, $decoded);

        // Preserve photos array if present in $decoded
        if (isset($decoded['photos']) && is_array($decoded['photos'])) {
            $merged['photos'] = $decoded['photos'];
        }

        return $merged;
    }

    public function setSection13FinalRemarksAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['section13_final_remarks'] = null;

            return;
        }

        $json = is_string($value) ? $value : json_encode($value);
        $encrypted = Crypt::encryptString($json);

        $this->attributes['section13_final_remarks'] = json_encode($encrypted);
    }

    /**
     * Retrieve all remarked checklist items across sections 1–13 with their evidence photo status.
     *
     * @return array<int, array{
     *     section_key: string,
     *     section_number: int,
     *     section_title: string,
     *     item_index: int|string,
     *     item_label: string,
     *     remarks: string,
     *     evidence_photo: ?string,
     *     photo_url: ?string,
     *     has_photo: bool,
     *     missing_photo: bool,
     *     field_name: string
     * }>
     */
    public function getAllRemarksWithEvidence(): array
    {
        $records = [];

        // 1. Legal Identity
        $sec1Items = $this->section1_legal ?? [];
        if (is_array($sec1Items)) {
            foreach ($sec1Items as $idx => $item) {
                $remarks = trim((string) ($item['remarks'] ?? ''));
                $photo = ! empty($item['evidence_photo']) ? (string) $item['evidence_photo'] : null;
                if ($remarks !== '' || $photo !== null) {
                    $records[] = [
                        'section_key' => 'section1_legal',
                        'section_number' => 1,
                        'section_title' => '1. Legal Identity & Recognition',
                        'item_index' => $idx,
                        'item_label' => ($item['point'] ?? 'Item '.($idx + 1)),
                        'remarks' => $remarks,
                        'evidence_photo' => $photo,
                        'photo_url' => $photo ? asset('storage/'.$photo) : null,
                        'has_photo' => ! empty($photo),
                        'missing_photo' => ($remarks !== '' && empty($photo)),
                        'field_name' => "section1_legal[{$idx}][evidence_photo]",
                    ];
                }
            }
        }

        // 2. Rooms; Dimensions & Capacity
        $sec3 = $this->section3_rooms ?? [];
        if (is_array($sec3)) {
            foreach ($sec3 as $idx => $room) {
                $remarks = trim((string) ($room['remarks'] ?? ''));
                $photo = ! empty($room['evidence_photo']) ? (string) $room['evidence_photo'] : null;
                if ($remarks !== '' || $photo !== null) {
                    $records[] = [
                        'section_key' => 'section3_rooms',
                        'section_number' => 2,
                        'section_title' => '2. Rooms; Dimensions & Capacity',
                        'item_index' => $idx,
                        'item_label' => ($room['type'] ?? 'Room Space '.($idx + 1)),
                        'remarks' => $remarks,
                        'evidence_photo' => $photo,
                        'photo_url' => $photo ? asset('storage/'.$photo) : null,
                        'has_photo' => ! empty($photo),
                        'missing_photo' => ($remarks !== '' && empty($photo)),
                        'field_name' => "section3_rooms[{$idx}][evidence_photo]",
                    ];
                }
            }
        }

        // 3. Physical Infrastructure & Campus Condition
        $sec2Items = $this->section2_infrastructure ?? [];
        if (is_array($sec2Items)) {
            foreach ($sec2Items as $idx => $item) {
                $remarks = trim((string) ($item['remarks'] ?? ''));
                $photo = ! empty($item['evidence_photo']) ? (string) $item['evidence_photo'] : null;
                if ($remarks !== '' || $photo !== null) {
                    $records[] = [
                        'section_key' => 'section2_infrastructure',
                        'section_number' => 3,
                        'section_title' => '3. Physical Infrastructure & Campus Condition',
                        'item_index' => $idx,
                        'item_label' => ($item['point'] ?? 'Item '.($idx + 1)),
                        'remarks' => $remarks,
                        'evidence_photo' => $photo,
                        'photo_url' => $photo ? asset('storage/'.$photo) : null,
                        'has_photo' => ! empty($photo),
                        'missing_photo' => ($remarks !== '' && empty($photo)),
                        'field_name' => "section2_infrastructure[{$idx}][evidence_photo]",
                    ];
                }
            }
        }

        // Standard remaining checklist sections (5. Student Population, 6. Curriculum, 7. Staff, 8. Financials, 9. Brand)
        $standardSections = [
            'section4_students' => ['number' => 5, 'title' => '5. Student Population & Admissions', 'label_key' => 'point'],
            'section6_academic' => ['number' => 6, 'title' => '6. Curriculum', 'label_key' => 'point'],
            'section7_staff' => ['number' => 7, 'title' => '7. Staff, HR & Liabilities', 'label_key' => 'point'],
            'section8_financial' => ['number' => 8, 'title' => '8. Financials – Income & Expense', 'label_key' => 'point'],
            'section9_brand' => ['number' => 9, 'title' => '9. Branding & Community', 'label_key' => 'point'],
        ];

        foreach ($standardSections as $secKey => $meta) {
            $items = $this->{$secKey} ?? [];
            if (is_array($items)) {
                foreach ($items as $idx => $item) {
                    $remarks = trim((string) ($item['remarks'] ?? ''));
                    $photo = ! empty($item['evidence_photo']) ? (string) $item['evidence_photo'] : null;
                    if ($remarks !== '' || $photo !== null) {
                        $records[] = [
                            'section_key' => $secKey,
                            'section_number' => $meta['number'],
                            'section_title' => $meta['title'],
                            'item_index' => $idx,
                            'item_label' => ($item[$meta['label_key']] ?? 'Item '.($idx + 1)),
                            'remarks' => $remarks,
                            'evidence_photo' => $photo,
                            'photo_url' => $photo ? asset('storage/'.$photo) : null,
                            'has_photo' => ! empty($photo),
                            'missing_photo' => ($remarks !== '' && empty($photo)),
                            'field_name' => "{$secKey}[{$idx}][evidence_photo]",
                        ];
                    }
                }
            }
        }

        // Section 10: Primary Assessment
        $sec10 = $this->section10_primary_assessment;
        $sec10Rows = $sec10['rows'] ?? (is_array($sec10) ? $sec10 : []);
        if (is_array($sec10Rows)) {
            foreach ($sec10Rows as $idx => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $remarks = trim((string) ($row['remarks'] ?? ''));
                $photo = ! empty($row['evidence_photo']) ? (string) $row['evidence_photo'] : null;
                if ($remarks !== '' || $photo !== null) {
                    $records[] = [
                        'section_key' => 'section10_primary_assessment',
                        'section_number' => 10,
                        'section_title' => '10. Primary Assessment Matrix',
                        'item_index' => $idx,
                        'item_label' => ($row['particular'] ?? 'Primary Competency '.($idx + 1)),
                        'remarks' => $remarks,
                        'evidence_photo' => $photo,
                        'photo_url' => $photo ? asset('storage/'.$photo) : null,
                        'has_photo' => ! empty($photo),
                        'missing_photo' => ($remarks !== '' && empty($photo)),
                        'field_name' => "section10_primary_assessment[rows][{$idx}][evidence_photo]",
                    ];
                }
            }
        }

        // Section 11: Secondary Assessment
        $sec11 = $this->section11_secondary_assessment;
        $sec11Rows = isset($sec11['rows']) && is_array($sec11['rows']) ? $sec11['rows'] : (is_array($sec11) ? $sec11 : []);
        if (is_array($sec11Rows)) {
            foreach ($sec11Rows as $idx => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $remarks = trim((string) ($row['remarks'] ?? ''));
                $photo = ! empty($row['evidence_photo']) ? (string) $row['evidence_photo'] : null;
                if ($remarks !== '' || $photo !== null) {
                    $records[] = [
                        'section_key' => 'section11_secondary_assessment',
                        'section_number' => 11,
                        'section_title' => '11. Secondary Assessment Matrix',
                        'item_index' => $idx,
                        'item_label' => ($row['particular'] ?? 'Secondary Competency '.($idx + 1)),
                        'remarks' => $remarks,
                        'evidence_photo' => $photo,
                        'photo_url' => $photo ? asset('storage/'.$photo) : null,
                        'has_photo' => ! empty($photo),
                        'missing_photo' => ($remarks !== '' && empty($photo)),
                        'field_name' => "section11_secondary_assessment[{$idx}][evidence_photo]",
                    ];
                }
            }
        }

        // Section 12: Teachers' Assessment
        $sec12 = $this->section12_teachers_assessment;
        $sec12Rows = $sec12['rows'] ?? (is_array($sec12) ? $sec12 : []);
        if (is_array($sec12Rows)) {
            foreach ($sec12Rows as $idx => $row) {
                if (! is_array($row)) {
                    continue;
                }
                $remarks = trim((string) ($row['remarks'] ?? ''));
                $photo = ! empty($row['evidence_photo']) ? (string) $row['evidence_photo'] : null;
                if ($remarks !== '' || $photo !== null) {
                    $records[] = [
                        'section_key' => 'section12_teachers_assessment',
                        'section_number' => 12,
                        'section_title' => "12. Teachers' Assessment Matrix",
                        'item_index' => $idx,
                        'item_label' => ($row['particular'] ?? 'Teacher Evaluation '.($idx + 1)),
                        'remarks' => $remarks,
                        'evidence_photo' => $photo,
                        'photo_url' => $photo ? asset('storage/'.$photo) : null,
                        'has_photo' => ! empty($photo),
                        'missing_photo' => ($remarks !== '' && empty($photo)),
                        'field_name' => "section12_teachers_assessment[rows][{$idx}][evidence_photo]",
                    ];
                }
            }
        }

        // Section 13: Final Remarks
        $sec13 = $this->section13_final_remarks ?? [];
        if (is_string($sec13)) {
            $decoded = json_decode($sec13, true);
            if (is_array($decoded)) {
                $sec13 = $decoded;
            } else {
                try {
                    $decrypted = Crypt::decryptString($sec13);
                    $sec13 = json_decode($decrypted, true) ?? [];
                } catch (\Throwable $e) {
                    $sec13 = [];
                }
            }
        }
        if (! is_array($sec13)) {
            $sec13 = [];
        }
        $sec13Categories = [
            'academic' => 'Academic Overview',
            'financial' => 'Financial Feasibility',
            'infrastructural' => 'Infrastructural Status',
            'legal' => 'Legal & Compliance',
            'administration_staff' => 'Administration Staff Evaluation',
            'teaching_staff' => 'Teaching Staff Evaluation',
            'safety' => 'Safety & Security',
        ];

        foreach ($sec13Categories as $catKey => $catLabel) {
            $remarks = trim((string) ($sec13[$catKey] ?? ''));
            $photo = $sec13['photos'][$catKey] ?? ($sec13[$catKey.'_photo'] ?? ($sec13[$catKey.'_evidence_photo'] ?? null));
            $photo = ! empty($photo) ? (string) $photo : null;
            if ($remarks !== '' || $photo !== null) {
                $records[] = [
                    'section_key' => 'section13_final_remarks',
                    'section_number' => 14,
                    'section_title' => '14. Final Assessment Remarks',
                    'item_index' => $catKey,
                    'item_label' => $catLabel,
                    'remarks' => $remarks,
                    'evidence_photo' => $photo,
                    'photo_url' => $photo ? asset('storage/'.$photo) : null,
                    'has_photo' => ! empty($photo),
                    'missing_photo' => ($remarks !== '' && empty($photo)),
                    'field_name' => "section13_final_remarks[photos][{$catKey}]",
                ];
            }
        }

        return $records;
    }

    /**
     * Compute summary statistics for remarks and evidence photos.
     *
     * @return array{
     *     total_remarks: int,
     *     photos_attached: int,
     *     missing_photos: int,
     *     is_complete: bool,
     *     items: array
     * }
     */
    public function getEvidenceSummary(): array
    {
        $items = $this->getAllRemarksWithEvidence();
        $totalRemarks = 0;
        $photosAttached = 0;
        $missingPhotos = 0;

        foreach ($items as $item) {
            if ($item['remarks'] !== '') {
                $totalRemarks++;
            }
            if ($item['has_photo']) {
                $photosAttached++;
            }
            if ($item['missing_photo']) {
                $missingPhotos++;
            }
        }

        return [
            'total_remarks' => $totalRemarks,
            'photos_attached' => $photosAttached,
            'missing_photos' => $missingPhotos,
            'is_complete' => $missingPhotos === 0,
            'items' => $items,
        ];
    }
}
