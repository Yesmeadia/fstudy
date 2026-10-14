<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\FeasibilityApplication;
use App\Models\FeasibilityChecklist;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChecklistEvidencePhotoTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected FeasibilityApplication $application;

    protected FeasibilityChecklist $checklist;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::where('email', 'superadmin@feasibility.test')->first() ?? User::factory()->create();
        if (! $this->user->hasRole('Super Admin')) {
            $this->user->assignRole('Super Admin');
        }

        $school = School::firstOrCreate(
            ['school_code' => 'SCH-TEST-001'],
            [
                'user_id' => $this->user->id,
                'name' => 'Green Valley Academy',
                'proposed_name' => 'Green Valley Academy',
                'management_organization' => 'Green Valley Educational Trust',
                'applicant_name' => 'Admin Officer',
                'applicant_email' => 'officer@test.com',
                'applicant_phone' => '+91 9999999999',
                'address' => '123 Academic Way',
                'state' => 'Kerala',
                'district' => 'Ernakulam',
                'city' => 'Kochi',
                'pincode' => '682001',
            ]
        );

        $this->application = FeasibilityApplication::firstOrCreate(
            ['application_number' => 'APP-TEST-2026-001'],
            [
                'school_id' => $school->id,
                'applicant_user_id' => $this->user->id,
                'status' => ApplicationStatus::DRAFT,
            ]
        );

        $this->checklist = FeasibilityChecklist::firstOrCreate(
            ['application_id' => $this->application->id],
            [
                'institution_name' => 'Green Valley Academy',
                'inspector_name' => 'Inspector John',
                'overall_recommendation' => 'recommended',
            ]
        );
    }

    public function test_inspector_can_upload_evidence_photo_via_ajax(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('evidence_boundary_wall.jpg', 800, 600)->size(1500);

        $response = $this->actingAs($this->user)->postJson(route('applications.checklist.upload_photo', $this->application), [
            'photo' => $file,
            'section' => 1,
            'item_key' => '0',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'path',
                'url',
                'filename',
            ]);

        $path = $response->json('path');
        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_evidence_photo_upload_validates_file_types(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->user)->postJson(route('applications.checklist.upload_photo', $this->application), [
            'photo' => $file,
            'section' => 2,
            'item_key' => '0',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['photo']);
    }

    public function test_evidence_photo_upload_validates_max_size_10mb(): void
    {
        Storage::fake('public');

        // 12MB image (12288 KB > 10240 KB limit)
        $file = UploadedFile::fake()->image('huge_photo.jpg')->size(12288);

        $response = $this->actingAs($this->user)->postJson(route('applications.checklist.upload_photo', $this->application), [
            'photo' => $file,
            'section' => 4,
            'item_key' => '0',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['photo']);
    }

    public function test_checklist_model_aggregates_remarks_and_evidence(): void
    {
        // Populate section 1 with remark but no photo (missing evidence)
        $this->checklist->section1_legal = [
            [
                'no' => 1,
                'point' => 'Legal name of the institution',
                'compliance' => 'no',
                'remarks' => 'Access route is narrow and needs widening',
                'evidence_photo' => null,
            ],
            [
                'no' => 2,
                'point' => 'Ownership of school land',
                'compliance' => 'yes',
                'remarks' => '',
            ],
        ];

        // Populate section 2 with remark AND photo
        $this->checklist->section2_infrastructure = [
            [
                'no' => 1,
                'point' => 'Rooms details',
                'compliance' => 'yes',
                'remarks' => 'Clean asphalt approach road as observed on site visit',
                'evidence_photo' => 'evidence_photos/1/approach_road.jpg',
            ],
        ];

        // Populate section 13 with remark and photo
        $this->checklist->section13_final_remarks = [
            'academic' => 'Recommended with minor civil upgrades needed',
            'photos' => [
                'academic' => 'evidence_photos/1/overall_view.jpg',
            ],
        ];

        $this->checklist->save();
        $this->checklist->refresh();

        $remarks = $this->checklist->getAllRemarksWithEvidence();
        $this->assertNotEmpty($remarks);

        // Find section 1 remark
        $sec1Remark = collect($remarks)->firstWhere('section_number', 1);
        $this->assertNotNull($sec1Remark);
        $this->assertTrue($sec1Remark['missing_photo']);
        $this->assertFalse($sec1Remark['has_photo']);

        // Find section 2 remark
        $sec2Remark = collect($remarks)->firstWhere('section_number', 2);
        $this->assertNotNull($sec2Remark);
        $this->assertFalse($sec2Remark['missing_photo']);
        $this->assertTrue($sec2Remark['has_photo']);
        $this->assertEquals('evidence_photos/1/approach_road.jpg', $sec2Remark['evidence_photo']);

        // Test evidence summary KPIs
        $summary = $this->checklist->getEvidenceSummary();
        $this->assertGreaterThanOrEqual(3, $summary['total_remarks']);
        $this->assertGreaterThanOrEqual(2, $summary['photos_attached']);
        $this->assertGreaterThanOrEqual(1, $summary['missing_photos']);
        $this->assertFalse($summary['is_complete']);
    }

    public function test_checklist_edit_renders_section_14_evidence_review(): void
    {
        $response = $this->actingAs($this->user)->get(route('applications.checklist.edit', $this->application));
        $response->assertStatus(200);
        $response->assertSee('Evidence Photos & Remarks Review', false);
        $response->assertSee('All Remarked Items');
        $response->assertSee('Without Photo');
        $response->assertSee('Take Photo using Camera');
        $response->assertSee('Select from Gallery / Photos');
    }

    public function test_section_14_final_assessment_remarks_can_be_edited_and_updated(): void
    {
        $payload = [
            'current_section' => '14',
            'action' => 'save_draft',
            'section13_final_remarks' => [
                'academic' => 'Academic curriculum follows CBSE pattern.',
                'financial' => 'Solid revenue model with no pending loans.',
                'infrastructural' => 'Good 3-storey building with science labs.',
                'legal' => 'All land title documents verified and clear.',
                'administration_staff' => 'Well-trained administrative leadership.',
                'teaching_staff' => '25 qualified teachers willing to continue.',
                'safety' => 'Fire safety certificate valid until 2028.',
                'signed_by' => 'Lead Inspector Officer',
                'photos' => [
                    'academic' => 'evidence_photos/1/academic_evidence.jpg',
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('applications.checklist.update', $this->application), $payload);

        $response->assertRedirect(route('applications.checklist.edit', [
            'application' => $this->application,
            'section' => '14',
        ]));

        $this->checklist->refresh();

        // Ensure remarks decrypted as array properly
        $remarks = $this->checklist->section13_final_remarks;
        $this->assertIsArray($remarks);
        $this->assertEquals('Academic curriculum follows CBSE pattern.', $remarks['academic']);
        $this->assertEquals('Solid revenue model with no pending loans.', $remarks['financial']);
        $this->assertEquals('Good 3-storey building with science labs.', $remarks['infrastructural']);
        $this->assertEquals('All land title documents verified and clear.', $remarks['legal']);
        $this->assertEquals('Well-trained administrative leadership.', $remarks['administration_staff']);
        $this->assertEquals('25 qualified teachers willing to continue.', $remarks['teaching_staff']);
        $this->assertEquals('Fire safety certificate valid until 2028.', $remarks['safety']);
        $this->assertEquals('Lead Inspector Officer', $remarks['signed_by']);
        $this->assertEquals('evidence_photos/1/academic_evidence.jpg', $remarks['photos']['academic']);

        // Check edit view renders the saved values inside Section 14
        $viewResponse = $this->actingAs($this->user)
            ->get(route('applications.checklist.edit', [
                'application' => $this->application,
                'section' => '14',
            ]));

        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Academic curriculum follows CBSE pattern.');
        $viewResponse->assertSee('Solid revenue model with no pending loans.');
        $viewResponse->assertSee('Lead Inspector Officer');

        // Check application show view displays Section 14 remarks
        $showResponse = $this->actingAs($this->user)
            ->get(route('applications.show', $this->application));

        $showResponse->assertStatus(200);
        $showResponse->assertSee('Academic curriculum follows CBSE pattern.');
        $showResponse->assertSee('Lead Inspector Officer');
    }
}
