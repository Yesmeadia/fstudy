<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_projections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('class_name'); // e.g., Pre-KG, LKG, UKG, Class 1 to 12
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unsignedInteger('current_potential')->default(0);
            $table->unsignedInteger('year_1_projection')->default(0);
            $table->unsignedInteger('year_2_projection')->default(0);
            $table->unsignedInteger('year_3_projection')->default(0);
            $table->unsignedInteger('year_4_projection')->default(0);
            $table->unsignedInteger('year_5_projection')->default(0);
            $table->unsignedSmallInteger('sections_required')->default(1);
            $table->unsignedSmallInteger('teachers_required')->default(1);
            $table->decimal('capacity_utilization_pct', 5, 2)->default(0);
            $table->timestamps();

            $table->index('application_id');
        });

        Schema::create('infrastructure_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Academic, Administration, Student Facilities, Safety
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('infrastructure_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('infrastructure_categories')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('infrastructure_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('infrastructure_items')->cascadeOnDelete();
            $table->string('status')->default('planned'); // available, not_available, planned, not_applicable
            $table->unsignedSmallInteger('quantity')->default(0);
            $table->decimal('score', 4, 1)->default(0); // 0 to 10
            $table->text('remarks')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infrastructure_assessments');
        Schema::dropIfExists('infrastructure_items');
        Schema::dropIfExists('infrastructure_categories');
        Schema::dropIfExists('student_projections');
    }
};
