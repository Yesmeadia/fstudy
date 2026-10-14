<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feasibility_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained('feasibility_applications')->cascadeOnDelete();

            // Header Info / Due Diligence Inspection Metadata
            $table->string('institution_name')->nullable();
            $table->string('location')->nullable();
            $table->string('present_management')->nullable();
            $table->string('inspection_dates')->nullable();
            $table->text('inspection_team')->nullable();

            // 13 Detailed Sections from Official YES India Foundation Format
            $table->json('section1_legal')->nullable();
            $table->json('section2_infrastructure')->nullable();
            $table->json('section3_rooms')->nullable();
            $table->json('section4_students')->nullable();
            $table->json('section5_enrolment')->nullable();
            $table->json('section6_academic')->nullable();
            $table->json('section7_staff')->nullable();
            $table->json('section8_financial')->nullable();
            $table->json('section9_brand')->nullable();
            $table->json('section10_primary_assessment')->nullable();
            $table->json('section11_secondary_assessment')->nullable();
            $table->json('section12_teachers_assessment')->nullable();
            $table->json('section13_final_remarks')->nullable();

            // Inspector Evaluation & Sign-off
            $table->text('overall_summary')->nullable();
            $table->string('overall_recommendation')->nullable(); // recommended, conditionally_recommended, not_recommended
            $table->string('inspector_name')->nullable();
            $table->timestamp('signed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feasibility_checklists');
    }
};
