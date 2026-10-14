<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Enums\BoardType;
use App\Enums\SchoolType;
use App\Models\FeasibilityApplication;
use App\Models\School;
use App\Models\User;
use App\Services\TwoFactor\TwoFactorAuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FeasibilityWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        if (! $admin) {
            $this->artisan('db:seed');
            $admin = User::where('email', 'admin@feasibility.test')->first();
        }

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Feasibility Study Executive Dashboard');
    }

    public function test_applications_directory_renders_successfully(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $response = $this->actingAs($admin)->get('/applications');
        $response->assertStatus(200);
        $response->assertSee('Feasibility Study Applications');
    }

    public function test_application_show_screen_displays_tabs(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $application = FeasibilityApplication::first();

        $response = $this->actingAs($admin)->get(route('applications.show', $application));
        $response->assertStatus(200);
        $response->assertSee($application->application_number);
        $response->assertSee('Overview');
        $response->assertSee('Financial Model');
    }

    public function test_wizard_step_one_renders_properly(): void
    {
        $applicant = User::where('email', 'applicant@feasibility.test')->first();
        $application = FeasibilityApplication::first();

        $response = $this->actingAs($applicant)->get(route('applications.wizard.step', [$application, 1]));
        $response->assertStatus(200);
        $response->assertSee('Step 1: School Identity');
    }

    public function test_pdf_report_generates_and_streams_successfully(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $application = FeasibilityApplication::where('status', ApplicationStatus::MANAGEMENT_REVIEW)->first()
            ?? FeasibilityApplication::first();

        $response = $this->actingAs($admin)->get(route('reports.application.pdf', $application));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_user_profile_page_renders_with_roles_and_permissions(): void
    {
        $user = User::where('email', 'officer@feasibility.test')->first();

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('My Profile');
        $response->assertSee('YES INDIA FOUNDATION');
        $response->assertSee('Feasibility Officer');
    }

    public function test_user_can_update_profile_information(): void
    {
        $user = User::where('email', 'officer@feasibility.test')->first();

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Updated Officer Name',
            'email' => $user->email,
            'phone' => '+91 9988776655',
            'designation' => 'Lead Zonal Evaluator',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Officer Name',
            'phone' => '+91 9988776655',
            'designation' => 'Lead Zonal Evaluator',
        ]);
    }

    public function test_user_can_upload_profile_photo_under_one_mb(): void
    {
        Storage::fake('public');
        $user = User::where('email', 'officer@feasibility.test')->first();

        // 500 KB valid image
        $file = UploadedFile::fake()->image('avatar.jpg')->size(500);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $file,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $user->refresh();
        $this->assertNotNull($user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_user_cannot_upload_profile_photo_exceeding_one_mb(): void
    {
        Storage::fake('public');
        $user = User::where('email', 'officer@feasibility.test')->first();

        // 1.5 MB image (1500 KB > 1024 KB)
        $file = UploadedFile::fake()->image('large_avatar.jpg')->size(1500);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $file,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_user_can_toggle_two_factor_authentication(): void
    {
        $user = User::where('email', 'officer@feasibility.test')->first();

        // Enable 2FA
        $response = $this->actingAs($user)->post(route('profile.two-factor'), [
            'enable' => '1',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertTrue($user->fresh()->two_factor_enabled);

        // Disable 2FA
        $response = $this->actingAs($user)->post(route('profile.two-factor'), [
            'enable' => '0',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertFalse($user->fresh()->two_factor_enabled);
    }

    public function test_profile_displays_totp_qr_code_and_recovery_codes(): void
    {
        $user = User::where('email', 'officer@feasibility.test')->first();

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Scan with Authenticator App');
        $response->assertSee('Emergency Backup Recovery Codes');
        $response->assertSee('Download (.txt)');
    }

    public function test_user_can_regenerate_emergency_backup_recovery_codes(): void
    {
        $user = User::where('email', 'officer@feasibility.test')->first();

        $response = $this->actingAs($user)->post(route('profile.two-factor.recovery-codes'));
        $response->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertIsArray($user->two_factor_recovery_codes);
        $this->assertCount(8, $user->two_factor_recovery_codes);
    }

    public function test_user_can_authenticate_using_emergency_recovery_code(): void
    {
        $twoFactorService = app(TwoFactorAuthenticationService::class);
        $user = User::where('email', 'officer@feasibility.test')->first();
        $codes = $twoFactorService->generateRecoveryCodes();
        $firstCode = $codes[0];

        $user->forceFill([
            'two_factor_enabled' => true,
            'two_factor_secret' => $twoFactorService->generateSecretKey(),
            'two_factor_recovery_codes' => $codes,
        ])->save();

        // Step 1: Normal credential check sets session
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('two-factor.challenge'));

        // Step 2: Use emergency recovery code
        $challengeResponse = $this->withSession([
            'login.id' => $user->id,
        ])->post(route('two-factor.verify'), [
            'code' => $firstCode,
        ]);

        $challengeResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        // Recovery code was consumed
        $user->refresh();
        $this->assertCount(7, $user->two_factor_recovery_codes);
        $this->assertFalse(in_array($firstCode, $user->two_factor_recovery_codes));

        // Clean up
        $user->forceFill(['two_factor_enabled' => false])->save();
    }

    public function test_super_admin_can_access_activity_audit_logs(): void
    {
        $superAdmin = User::where('email', 'superadmin@feasibility.test')->first();

        $response = $this->actingAs($superAdmin)->get(route('audit_logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Super Admin Activity');
        $response->assertSee('Immutable security log');
    }

    public function test_applicant_cannot_access_system_audit_logs(): void
    {
        $applicant = User::where('email', 'applicant@feasibility.test')->first();

        $response = $this->actingAs($applicant)->get(route('audit_logs.index'));
        $response->assertStatus(403);
    }

    public function test_security_headers_are_applied_to_web_responses(): void
    {
        $response = $this->get('/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
    }

    public function test_soft_deletes_preserve_record_in_database_without_hard_delete(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $school = School::create([
            'user_id' => $admin->id,
            'name' => 'Temporary School To Delete',
            'school_code' => 'SCH-DEL-001',
            'applicant_name' => 'Test Applicant',
            'applicant_email' => 'temp@test.com',
            'applicant_phone' => '1234567890',
            'management_organization' => 'Test Trust',
            'trust_society_type' => 'Trust',
            'board' => BoardType::CBSE,
            'school_type' => SchoolType::K12,
            'address' => '123 Street',
            'city' => 'City',
            'district' => 'District',
            'state' => 'Delhi',
            'pincode' => '110001',
        ]);

        $response = $this->actingAs($admin)->delete(route('schools.destroy', $school));
        $response->assertRedirect(route('schools.index'));

        // Assert record is soft-deleted (deleted_at is set, row still exists in DB)
        $this->assertSoftDeleted('schools', [
            'id' => $school->id,
        ]);
        $this->assertDatabaseHas('schools', [
            'id' => $school->id,
        ]);
    }

    public function test_applications_create_screen_renders_successfully(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();

        $response = $this->actingAs($admin)->get(route('applications.create'));
        $response->assertStatus(200);
        $response->assertSee('Initiate Running School Takeover Feasibility Inspection');
    }

    public function test_applications_create_view_renders_safely_when_variable_omitted(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();

        // Simulate rendering the view without $schoolsWithActiveApplications passed
        $view = $this->actingAs($admin)->view('applications.create', [
            'schools' => School::all(),
            'inspectors' => User::role('Inspector')->get(['id', 'name']),
        ]);

        $rendered = (string) $view;
        $this->assertStringContainsString('Initiate Running School Takeover Feasibility Inspection', $rendered);
    }

    public function test_cannot_create_application_without_primary_details(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $school = School::first();

        $response = $this->actingAs($admin)->post(route('applications.store'), [
            'school_id' => $school->id,
            // primary details omitted
        ]);

        $response->assertSessionHasErrors([
            'institution_name',
            'location',
            'present_management',
            'inspection_dates',
            'inspection_team',
        ]);
    }

    public function test_can_create_application_with_primary_details_and_persists_to_checklist(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $school = School::create([
            'user_id' => $admin->id,
            'name' => 'Primary Detail School Test',
            'school_code' => 'SCH-TEST-PRIM-01',
            'applicant_name' => 'Officer Test',
            'applicant_email' => 'officer.test@school.test',
            'applicant_phone' => '9988776655',
            'management_organization' => 'Apex Educational Society',
            'trust_society_type' => 'Trust',
            'board' => BoardType::CBSE,
            'school_type' => SchoolType::K12,
            'address' => '45 Knowledge Park',
            'city' => 'Kochi',
            'district' => 'Ernakulam',
            'state' => 'Kerala',
            'pincode' => '682001',
        ]);

        $response = $this->actingAs($admin)->post(route('applications.store'), [
            'school_id' => $school->id,
            'institution_name' => 'Primary Detail School Test',
            'location' => 'Kochi, Kerala',
            'present_management' => 'Apex Educational Society',
            'inspection_dates' => '24-25 September 2026',
            'inspection_team' => 'Dr. Inspector & Evaluator Team',
        ]);

        $response->assertRedirect();
        $application = FeasibilityApplication::where('school_id', $school->id)->latest()->first();
        $this->assertNotNull($application);

        $checklist = $application->checklist;
        $this->assertNotNull($checklist);
        $this->assertEquals('24-25 September 2026', $checklist->inspection_dates);
        $this->assertEquals('Dr. Inspector & Evaluator Team', $checklist->inspection_team);
        $this->assertEquals('Primary Detail School Test', $checklist->institution_name);
    }

    public function test_checklist_defaults_to_section_0_when_inspection_dates_is_empty(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $application = FeasibilityApplication::first();
        $checklist = $application->checklist;
        $checklist->update(['inspection_dates' => null]);

        $response = $this->actingAs($admin)->get(route('applications.checklist.edit', $application));
        $response->assertStatus(200);
        $response->assertSee('0. Primary Details &amp; Inspection Context', false);
    }

    public function test_checklist_can_save_section_0_primary_details(): void
    {
        $admin = User::where('email', 'admin@feasibility.test')->first();
        $application = FeasibilityApplication::first();

        $response = $this->actingAs($admin)->post(route('applications.checklist.update', $application), [
            'current_section' => '0',
            'action' => 'save_and_next',
            'institution_name' => 'Updated School Name',
            'location' => 'Calicut, Kerala',
            'present_management' => 'New Management Group',
            'inspection_dates' => '28-29 September 2026',
            'inspection_team' => 'Lead Auditor Team',
        ]);

        $response->assertRedirect(route('applications.checklist.edit', ['application' => $application, 'section' => 1]));
        $checklist = $application->checklist->fresh();
        $this->assertEquals('Updated School Name', $checklist->institution_name);
        $this->assertEquals('28-29 September 2026', $checklist->inspection_dates);
        $this->assertEquals('Lead Auditor Team', $checklist->inspection_team);
    }

    public function test_super_admin_can_view_user_management_with_role_indicators(): void
    {
        $superAdmin = User::where('email', 'superadmin@feasibility.test')->first();
        if (! $superAdmin) {
            $superAdmin = User::factory()->create();
            $superAdmin->assignRole('Super Admin');
        }

        $response = $this->actingAs($superAdmin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('User Management');
        $response->assertSee('Super Admins');
        $response->assertSee('Inspectors');
        $response->assertSee('Super Admin');

        // Test filtering by Inspector role
        $filterResponse = $this->actingAs($superAdmin)->get(route('users.index', ['role' => 'Inspector']));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Inspectors');
    }

    public function test_super_admin_can_create_school_and_directly_proceed_to_start_inspection(): void
    {
        $superAdmin = User::where('email', 'superadmin@feasibility.test')->first();
        if (! $superAdmin) {
            $superAdmin = User::factory()->create();
            $superAdmin->assignRole('Super Admin');
        }

        $schoolData = [
            'name' => 'Green Valley International Academy',
            'applicant_name' => 'Dr. Farhan Qasim',
            'applicant_email' => 'farhan@greenvalley.edu',
            'applicant_phone' => '9847123456',
            'management_organization' => 'Green Valley Trust',
            'trust_society_type' => 'Registered Charitable Trust',
            'address' => 'Plot 44, Knowledge City',
            'state' => 'Kerala',
            'district' => 'Kozhikode',
            'city' => 'Calicut',
            'pincode' => '673001',
            'board' => BoardType::CBSE->value,
            'medium' => 'English',
            'school_type' => SchoolType::K12->value,
            'action' => 'start_inspection',
        ];

        $response = $this->actingAs($superAdmin)->post(route('schools.store'), $schoolData);

        $school = School::where('name', 'Green Valley International Academy')->first();
        $this->assertNotNull($school);
        $response->assertRedirect(route('applications.create', ['school_id' => $school->id]));
        $response->assertSessionHas('success');
    }

    public function test_multiple_super_admins_can_concurrently_inspect_and_edit_same_school_checklist(): void
    {
        // Super Admin 1
        $superAdmin1 = User::where('email', 'superadmin@feasibility.test')->first();
        if (! $superAdmin1) {
            $superAdmin1 = User::factory()->create();
            $superAdmin1->assignRole('Super Admin');
        }

        // Super Admin 2
        $superAdmin2 = User::factory()->create([
            'name' => 'Second Super Admin',
            'email' => 'superadmin2@feasibility.test',
        ]);
        $superAdmin2->assignRole('Super Admin');

        $application = FeasibilityApplication::first();
        $this->assertNotNull($application);

        // Super Admin 2 visits the checklist started by or assigned to anyone
        $response = $this->actingAs($superAdmin2)->get(route('applications.checklist.edit', $application));
        $response->assertStatus(200);

        // Super Admin 2 can save section details
        $updateResponse = $this->actingAs($superAdmin2)->post(route('applications.checklist.update', $application), [
            'current_section' => '0',
            'action' => 'save_and_stay',
            'institution_name' => 'Joint Inspection Campus',
            'location' => 'Kochi, Kerala',
            'present_management' => 'Global Education Trust',
            'inspection_dates' => '24-25 September 2026',
            'inspection_team' => 'Super Admin 1 & Super Admin 2',
        ]);

        $updateResponse->assertRedirect(route('applications.checklist.edit', ['application' => $application, 'section' => 0]));
        $checklist = $application->checklist->fresh();
        $this->assertEquals('Joint Inspection Campus', $checklist->institution_name);
        $this->assertEquals('Super Admin 1 & Super Admin 2', $checklist->inspection_team);
    }
}

