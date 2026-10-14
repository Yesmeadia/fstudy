<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('state');
            $table->string('district');
            $table->string('taluk_tehsil')->nullable();
            $table->string('village_town');
            $table->text('address');
            $table->string('pincode', 20);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('nearest_major_road')->nullable();
            $table->decimal('distance_from_main_road_km', 8, 2)->nullable();
            $table->text('accessibility_description')->nullable();
            $table->boolean('public_transport_available')->default(true);
            $table->text('transport_details')->nullable();
            $table->timestamps();
        });

        Schema::create('land_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained('feasibility_applications')->cascadeOnDelete();
            $table->decimal('total_land_area', 10, 2);
            $table->string('area_unit')->default('Acres'); // Acres, Sq. Meters, Sq. Feet, Hectares
            $table->string('ownership_type')->default('owned'); // owned, leased, rented, trust_owned
            $table->string('survey_number')->nullable();
            $table->string('land_registration_details')->nullable();
            $table->date('lease_start_date')->nullable();
            $table->date('lease_expiry_date')->nullable();
            $table->decimal('building_area', 10, 2)->nullable(); // Sq. Feet
            $table->decimal('playground_area', 10, 2)->nullable();
            $table->decimal('open_area', 10, 2)->nullable();
            $table->boolean('is_land_title_clear')->default(true);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('demographic_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained('feasibility_applications')->cascadeOnDelete();
            $table->unsignedInteger('population_1km')->default(0);
            $table->unsignedInteger('population_3km')->default(0);
            $table->unsignedInteger('population_5km')->default(0);
            $table->unsignedInteger('number_of_households')->default(0);
            $table->unsignedInteger('children_age_3_5')->default(0);
            $table->unsignedInteger('children_age_6_10')->default(0);
            $table->unsignedInteger('children_age_11_14')->default(0);
            $table->unsignedInteger('children_age_15_18')->default(0);
            $table->decimal('population_growth_rate', 5, 2)->default(2.50); // percentage
            $table->string('residential_development_trend')->default('steady'); // rapid, steady, emerging, stagnant
            $table->string('employment_profile')->default('mixed'); // corporate, service, business, agriculture, mixed
            $table->string('economic_profile')->default('middle_income'); // high_income, upper_middle, middle_income, lower_middle
            $table->decimal('expected_capture_rate', 5, 2)->default(5.00); // e.g. 5%
            $table->unsignedInteger('potential_students_calculated')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('nearby_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('feasibility_applications')->cascadeOnDelete();
            $table->string('school_name');
            $table->decimal('distance_km', 6, 2);
            $table->string('board')->default('cbse');
            $table->string('medium')->default('English');
            $table->string('classes_offered')->default('Pre-KG to 12');
            $table->unsignedInteger('student_strength')->default(0);
            $table->decimal('fee_structure_annual', 12, 2)->default(0);
            $table->string('infrastructure_quality')->default('good'); // excellent, good, average, basic
            $table->boolean('transportation_offered')->default(true);
            $table->string('competition_level')->default('medium'); // low, medium, high
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nearby_schools');
        Schema::dropIfExists('demographic_assessments');
        Schema::dropIfExists('land_details');
        Schema::dropIfExists('locations');
    }
};
