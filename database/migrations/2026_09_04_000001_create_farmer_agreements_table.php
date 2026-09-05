<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_agreements', function (Blueprint $table) {
            $table->id();

            // Parties
            $table->unsignedInteger('first_party_id')->nullable()->comment('alias_company.com_id');
            $table->unsignedInteger('farmer_id')->nullable()->comment('farmers.fid');
            $table->unsignedInteger('organiser_id')->nullable()->comment('organiser.oid');
            $table->string('pi_apm_tpm', 100)->nullable();
            $table->string('production_executive', 100)->nullable();

            // Agreement details
            $table->date('agreement_date')->nullable();
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();

            // FS Code / Variety
            $table->unsignedInteger('female_variety_id')->nullable()->comment('alias_veriety.ver_id');
            $table->unsignedInteger('male_variety_id')->nullable()->comment('alias_veriety.ver_id');
            $table->unsignedInteger('crop_id')->nullable()->comment('core_crop.id');
            $table->string('production_code', 50)->nullable();
            $table->string('variety_type', 30)->nullable()->comment('HYB, OPV etc');

            // Annexure — Soil Details
            $table->string('water_availability', 50)->nullable();
            $table->string('topography', 50)->nullable();
            $table->string('land_type', 50)->nullable();
            $table->string('soil_type', 50)->nullable();
            $table->string('extent_of_cultivability', 50)->nullable();

            // Annexure — I QC%
            $table->decimal('qc_percent', 5, 2)->nullable();

            // Annexure — II Incentive
            $table->text('incentive_details')->nullable();

            // Annexure — III Additional
            $table->text('additional_details')->nullable();

            // Annexure — IV Estimated Yield
            $table->decimal('estimated_yield', 10, 3)->nullable();

            // Annexure — IV(A) Loss of Yield
            $table->decimal('loss_of_yield', 10, 3)->nullable();

            // Annexure — V Cost of FS Seed
            $table->decimal('cost_of_fs_seed', 10, 2)->nullable();

            // Status & audit
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->unsignedBigInteger('cr_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_agreements');
    }
};
