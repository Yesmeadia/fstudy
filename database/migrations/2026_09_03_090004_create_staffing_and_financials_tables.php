<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staffing_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('teaching'); // teaching, administrative, operational, support
            $table->decimal('default_monthly_salary', 10, 2)->default(30000);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('staffing_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('staffing_positions')->cascadeOnDelete();
            $table->unsignedSmallInteger('required_qty')->default(1);
            $table->unsignedSmallInteger('existing_qty')->default(0);
            $table->unsignedSmallInteger('additional_qty')->default(1);
            $table->decimal('monthly_salary', 10, 2)->default(0);
            $table->decimal('annual_cost', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['application_id', 'position_id']);
        });

        Schema::create('financial_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained('feasibility_applications')->cascadeOnDelete();
            $table->decimal('total_capex', 14, 2)->default(0);
            $table->decimal('annual_opex', 14, 2)->default(0);
            $table->decimal('annual_revenue', 14, 2)->default(0);
            $table->decimal('gross_surplus', 14, 2)->default(0);
            $table->decimal('net_surplus', 14, 2)->default(0);
            $table->decimal('ebitda', 14, 2)->default(0);
            $table->unsignedInteger('break_even_students')->default(0);
            $table->decimal('roi_percentage', 6, 2)->default(0);
            $table->decimal('payback_period_years', 4, 1)->default(0);
            $table->json('five_year_summary')->nullable();
            $table->text('financial_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('capital_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('category'); // land, building, furniture, laboratory, computer_equipment, library, buses, it_infrastructure, security, other
            $table->string('item_name');
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });

        Schema::create('operating_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('category'); // salaries, electricity, maintenance, transportation, marketing, administration, insurance, technology, miscellaneous
            $table->string('item_name');
            $table->decimal('annual_amount', 14, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });

        Schema::create('revenue_projections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('fee_type'); // admission_fee, tuition_fee, transport_fee, activity_fee, other_revenue
            $table->decimal('fee_per_student', 10, 2)->default(0);
            $table->decimal('annual_total', 14, 2)->default(0);
            $table->decimal('year_1', 14, 2)->default(0);
            $table->decimal('year_2', 14, 2)->default(0);
            $table->decimal('year_3', 14, 2)->default(0);
            $table->decimal('year_4', 14, 2)->default(0);
            $table->decimal('year_5', 14, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revenue_projections');
        Schema::dropIfExists('operating_expenses');
        Schema::dropIfExists('capital_expenses');
        Schema::dropIfExists('financial_assessments');
        Schema::dropIfExists('staffing_assessments');
        Schema::dropIfExists('staffing_positions');
    }
};
