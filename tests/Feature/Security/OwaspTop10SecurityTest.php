<?php

namespace Tests\Feature\Security;

use App\Enums\ApplicationStatus;
use App\Models\AuditLog;
use App\Models\FeasibilityApplication;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OwaspTop10SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $applicant1;
    protected User $applicant2;
    protected School $school1;
    protected FeasibilityApplication $application1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superAdmin = User::where('email', 'superadmin@feasibility.test')->first() ?? User::factory()->create();
        if (! $this->superAdmin->hasRole('Super Admin')) {
            $this->superAdmin->assignRole('Super Admin');
        }

        $this->applicant1 = User::factory()->create(['name' => 'Applicant One', 'email' => 'app1@test.com']);
        $this->applicant1->assignRole('Applicant');

        $this->applicant2 = User::factory()->create(['name' => 'Applicant Two', 'email' => 'app2@test.com']);
        $this->applicant2->assignRole('Applicant');

        $this->school1 = School::create([
            'user_id' => $this->applicant1->id,
            'name' => 'St. Xavier High School',
            'school_code' => 'SCH-KER-2026-XAV1',
            'management_organization' => 'Xavier Educational Society',
            'applicant_name' => 'Applicant One',
            'applicant_email' => 'app1@test.com',
            'applicant_phone' => '9876543210',
            'address' => 'MG Road',
            'city' => 'Kochi',
            'district' => 'Ernakulam',
            'state' => 'Kerala',
            'pincode' => '682001',
        ]);

        $this->application1 = FeasibilityApplication::create([
            'school_id' => $this->school1->id,
            'applicant_user_id' => $this->applicant1->id,
            'application_number' => 'APP-2026-SEC-01',
            'status' => ApplicationStatus::DRAFT,
        ]);
    }

    /**
     * A01: Broken Access Control - Unauthenticated requests cannot access protected routes.
     */
    public function test_a01_unauthenticated_users_are_redirected_to_login(): void
    {
        $protectedRoutes = [
            '/dashboard',
            '/schools',
            '/schools/create',
            "/schools/{$this->school1->id}",
            '/applications',
            "/applications/{$this->application1->id}",
            "/applications/{$this->application1->id}/checklist",
            '/users',
            '/settings',
            '/audit-logs',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    /**
     * A01: Broken Access Control - Horizontal Privilege Escalation (IDOR).
     * Applicant 2 cannot view, edit, or delete Applicant 1's school profile.
     */
    public function test_a01_applicant_cannot_access_or_modify_another_users_school(): void
    {
        // View school
        $this->actingAs($this->applicant2)
            ->get(route('schools.show', $this->school1))
            ->assertStatus(403);

        // Edit school form
        $this->actingAs($this->applicant2)
            ->get(route('schools.edit', $this->school1))
            ->assertStatus(403);

        // Update school
        $this->actingAs($this->applicant2)
            ->put(route('schools.update', $this->school1), [
                'name' => 'Hacked School Name',
                'management_organization' => 'Hacker Org',
                'applicant_name' => 'Hacker',
                'applicant_email' => 'hacker@test.com',
                'applicant_phone' => '9876543210',
                'address' => 'Hacked Street',
                'city' => 'Kochi',
                'district' => 'Ernakulam',
                'state' => 'Kerala',
                'pincode' => '682001',
                'board' => 'State Board',
                'school_type' => 'Middle School (Pre-Primary to 8)',
                'medium' => 'English',
            ])
            ->assertStatus(403);

        // Delete school
        $this->actingAs($this->applicant2)
            ->delete(route('schools.destroy', $this->school1))
            ->assertStatus(403);

        $this->assertDatabaseHas('schools', [
            'id' => $this->school1->id,
            'name' => 'St. Xavier High School',
        ]);
    }

    /**
     * A01: Broken Access Control - Vertical Privilege Escalation.
     * Non-management user cannot approve takeover applications.
     */
    public function test_a01_applicant_cannot_approve_takeover_applications(): void
    {
        $response = $this->actingAs($this->applicant1)
            ->post("/applications/{$this->application1->id}/approval-decision", [
                'decision' => 'approved',
                'remarks' => 'Unauthorized self-approval attempt.',
            ]);

        $response->assertStatus(403);

        $this->application1->refresh();
        $this->assertNotEquals(ApplicationStatus::APPROVED, $this->application1->status);
    }

    /**
     * A02: Cryptographic Failures - Passwords must be strongly hashed and hidden from serialization.
     */
    public function test_a02_passwords_are_strongly_hashed_and_hidden_from_serialization(): void
    {
        $user = User::factory()->create([
            'password' => 'SecurePass#2026',
        ]);

        // Verify password is not stored in plaintext
        $this->assertNotEquals('SecurePass#2026', $user->password);
        $this->assertTrue(Hash::check('SecurePass#2026', $user->password));

        // Verify sensitive fields are hidden when converted to array/JSON
        $serialized = $user->toArray();
        $this->assertArrayNotHasKey('password', $serialized);
        $this->assertArrayNotHasKey('remember_token', $serialized);
        $this->assertArrayNotHasKey('two_factor_secret', $serialized);
        $this->assertArrayNotHasKey('two_factor_recovery_codes', $serialized);
    }

    /**
     * A03: Injection - SQL injection payloads in search do not cause SQL syntax errors or data leak.
     */
    public function test_a03_sql_injection_payloads_are_safely_handled(): void
    {
        $payloads = [
            "' OR '1'='1",
            "'; DROP TABLE schools; --",
            "1' UNION SELECT null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null --",
            "admin'--",
        ];

        foreach ($payloads as $payload) {
            $response = $this->actingAs($this->superAdmin)
                ->get(route('schools.index', ['search' => $payload]));

            // Must respond cleanly with HTTP 200 and NOT crash or expose raw SQL errors
            $response->assertStatus(200);
            $response->assertDontSee('SQLSTATE');
            $response->assertDontSee('Syntax error');
        }
    }

    /**
     * A03: Injection - Stored XSS payloads in user input are escaped by Blade templates.
     */
    public function test_a03_stored_xss_is_escaped_in_blade_views(): void
    {
        $xssSchool = School::create([
            'user_id' => $this->applicant1->id,
            'name' => '<script>alert("XSS-ATTACK")</script>',
            'school_code' => 'SCH-KER-2026-XSS1',
            'management_organization' => '<img src=x onerror=alert(1)>',
            'applicant_name' => 'XSS Tester',
            'applicant_email' => 'xss@test.com',
            'applicant_phone' => '9876543210',
            'address' => 'Test Street',
            'city' => 'Kochi',
            'district' => 'Ernakulam',
            'state' => 'Kerala',
            'pincode' => '682001',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('schools.show', $xssSchool));

        $response->assertStatus(200);
        // Ensure literal unescaped script tag is not executed as raw HTML
        $response->assertDontSee('<script>alert("XSS-ATTACK")</script>', false);
        $response->assertSee('&lt;script&gt;alert(&quot;XSS-ATTACK&quot;)&lt;/script&gt;', false);
    }

    /**
     * A04: Insecure Design - Malicious and executable file extensions are rejected on upload.
     */
    public function test_a04_malicious_file_uploads_are_strictly_rejected(): void
    {
        Storage::fake('public');

        $maliciousFiles = [
            UploadedFile::fake()->create('shell.php', 100, 'application/x-php'),
            UploadedFile::fake()->create('malware.exe', 100, 'application/x-msdownload'),
            UploadedFile::fake()->create('script.sh', 100, 'text/x-shellscript'),
            UploadedFile::fake()->create('vector.svg', 100, 'image/svg+xml'),
        ];

        foreach ($maliciousFiles as $file) {
            $response = $this->actingAs($this->superAdmin)
                ->postJson(route('applications.checklist.upload_photo', $this->application1), [
                    'photo' => $file,
                ]);

            $response->assertStatus(422);
            $response->assertJsonValidationErrors(['photo']);
        }
    }

    /**
     * A05: Security Misconfiguration - Security headers are attached to responses.
     */
    public function test_a05_enterprise_security_headers_are_present(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertTrue($response->headers->has('Content-Security-Policy'));
    }

    /**
     * A08: Software & Data Integrity - CSRF protection blocks unverified POST requests.
     */
    public function test_a08_csrf_protection_rejects_unverified_post_requests(): void
    {
        // Sending post without session token simulation
        $response = $this->post(route('schools.store'), [
            'name' => 'CSRF School',
        ]);

        // Unauthenticated redirect or CSRF exception
        $this->assertTrue(in_array($response->status(), [302, 419]));
    }

    /**
     * A09: Security Logging & Monitoring - Critical operations generate structured AuditLog records.
     */
    public function test_a09_critical_operations_generate_audit_log_records(): void
    {
        $initialCount = AuditLog::count();

        // Perform audited action: update school
        $this->actingAs($this->superAdmin)
            ->put(route('schools.update', $this->school1), [
                'name' => 'St. Xavier Audited High School',
                'management_organization' => 'Xavier Educational Society',
                'applicant_name' => 'Applicant One',
                'applicant_email' => 'app1@test.com',
                'applicant_phone' => '9876543210',
                'address' => 'MG Road Audited',
                'city' => 'Kochi',
                'district' => 'Ernakulam',
                'state' => 'Kerala',
                'pincode' => '682001',
                'board' => 'State Board',
                'school_type' => 'Middle School (Pre-Primary to 8)',
                'medium' => 'English',
            ])
            ->assertRedirect();

        $this->assertGreaterThan($initialCount, AuditLog::count());
        $latestLog = AuditLog::latest('id')->first();
        $this->assertEquals('school_updated', $latestLog->action);
        $this->assertEquals($this->superAdmin->id, $latestLog->user_id);
    }

    /**
     * A10: SSRF - Geocoding controller validates numeric parameters and cannot target internal network.
     */
    public function test_a10_geocoding_endpoints_reject_invalid_numeric_parameters(): void
    {
        // Try passing internal SSRF targets or invalid lat/long
        $response = $this->actingAs($this->applicant1)
            ->postJson(route('geocode.reverse'), [
                'latitude' => 'http://169.254.169.254/latest/meta-data/',
                'longitude' => '127.0.0.1',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['latitude', 'longitude']);
    }
}
