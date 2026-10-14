<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->string('allowed_extensions')->default('pdf,jpg,jpeg,png,docx');
            $table->unsignedInteger('max_size_kb')->default(5120); // 5MB
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->foreignId('requirement_id')->constrained('document_requirements')->cascadeOnDelete();
            $table->string('original_filename');
            $table->string('storage_path');
            $table->string('mime_type');
            $table->unsignedInteger('file_size_bytes');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('version')->default(1);
            $table->string('verification_status')->default('uploaded')->index(); // pending, uploaded, under_verification, verified, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });

        Schema::create('field_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->foreignId('officer_id')->constrained('users')->cascadeOnDelete();
            $table->date('visit_date');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('representative_name')->nullable();
            $table->string('representative_contact')->nullable();
            $table->text('general_remarks')->nullable();
            $table->string('overall_recommendation')->default('recommended'); // recommended, conditionally_recommended, not_recommended
            $table->string('report_file_path')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });

        Schema::create('field_visit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_visit_id')->constrained('field_visits')->cascadeOnDelete();
            $table->string('item_key'); // land, building, location, population, nearby_schools, infrastructure, documents, management
            $table->string('item_name');
            $table->boolean('is_verified')->default(false);
            $table->text('remarks')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_visit_items');
        Schema::dropIfExists('field_visits');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_requirements');
    }
};
