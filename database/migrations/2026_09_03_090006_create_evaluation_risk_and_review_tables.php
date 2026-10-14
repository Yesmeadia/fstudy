<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Location, Demographics, Student Potential, Infrastructure, Competition, Financial Feasibility, Management Capability
            $table->decimal('weight_percentage', 5, 2); // e.g., 15.00, 20.00
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('evaluation_subcriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')->constrained('evaluation_criteria')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('max_score', 5, 2)->default(10.00);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->foreignId('subcriterion_id')->constrained('evaluation_subcriteria')->cascadeOnDelete();
            $table->decimal('score_obtained', 5, 2)->default(0);
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'subcriterion_id']);
            $table->index('application_id');
        });

        Schema::create('risk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Financial, Location, Competition, Student Demand, Infrastructure, Staffing, Regulatory, Management
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('risk_categories')->cascadeOnDelete();
            $table->text('risk_description');
            $table->unsignedTinyInteger('probability')->default(2); // 1 to 5
            $table->unsignedTinyInteger('impact')->default(3); // 1 to 5
            $table->unsignedSmallInteger('risk_score')->default(6); // probability * impact
            $table->string('risk_level')->default('medium'); // low, medium, high, critical
            $table->text('mitigation_strategy');
            $table->string('responsible_person')->nullable();
            $table->string('status')->default('open'); // open, mitigated, accepted, closed
            $table->timestamps();

            $table->index('application_id');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('section'); // academic, financial, infrastructure, legal, preliminary
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, under_review, approved, needs_correction, rejected
            $table->text('comments')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('risks');
        Schema::dropIfExists('risk_categories');
        Schema::dropIfExists('evaluation_scores');
        Schema::dropIfExists('evaluation_subcriteria');
        Schema::dropIfExists('evaluation_criteria');
    }
};
