<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('designation')->nullable()->after('phone');
        });

        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('proposed_name')->nullable();
            $table->string('school_code')->unique()->nullable();
            $table->string('applicant_name');
            $table->string('applicant_email')->nullable();
            $table->string('applicant_phone');
            $table->string('management_organization');
            $table->string('trust_society_type')->default('Trust'); // Trust, Society, Section 8 Company, etc.
            $table->string('registration_number')->nullable();
            $table->date('registration_date')->nullable();
            $table->text('address');
            $table->string('state');
            $table->string('district');
            $table->string('city');
            $table->string('pincode', 20);
            $table->string('website')->nullable();
            $table->integer('established_year')->nullable();
            $table->boolean('is_existing_school')->default(false);
            $table->string('board')->default('cbse');
            $table->string('medium')->default('English');
            $table->string('school_type')->default('k12');
            $table->timestamps();

            $table->index('state');
            $table->index('district');
        });

        Schema::create('feasibility_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique()->index();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('applicant_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft')->index();
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->unsignedTinyInteger('completion_percentage')->default(0);
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->string('feasibility_rating')->nullable();
            $table->timestamp('submission_date')->nullable();
            $table->boolean('declaration_accepted')->default(false);
            $table->string('declaration_signed_by')->nullable();
            $table->timestamp('declaration_date')->nullable();
            $table->string('management_decision')->nullable(); // approved, conditionally_approved, returned, rejected
            $table->timestamp('management_decision_at')->nullable();
            $table->foreignId('management_decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('management_remarks')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('application_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_histories');
        Schema::dropIfExists('feasibility_applications');
        Schema::dropIfExists('schools');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'designation']);
        });
    }
};
