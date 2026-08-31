<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('core_country')) {
            Schema::create('core_country', function (Blueprint $table) {
                $table->id();
                $table->string('country_name');
                $table->string('country_code')->nullable();
                $table->string('global_region')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('core_state')) {
            Schema::create('core_state', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->nullable()->constrained('core_country')->nullOnDelete();
                $table->string('state_name');
                $table->string('state_code')->nullable();
                $table->string('state_type')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('core_district')) {
            Schema::create('core_district', function (Blueprint $table) {
                $table->id();
                $table->foreignId('state_id')->nullable()->constrained('core_state')->nullOnDelete();
                $table->string('district_name');
                $table->string('district_code')->nullable();
                $table->string('numeric_code')->nullable();
                $table->date('effective_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('core_block')) {
            Schema::create('core_block', function (Blueprint $table) {
                $table->id();
                $table->foreignId('state_id')->nullable()->constrained('core_state')->nullOnDelete();
                $table->foreignId('district_id')->nullable()->constrained('core_district')->nullOnDelete();
                $table->string('block_name');
                $table->string('block_code')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('core_city')) {
            Schema::create('core_city', function (Blueprint $table) {
                $table->id();
                $table->foreignId('state_id')->nullable()->constrained('core_state')->nullOnDelete();
                $table->foreignId('district_id')->nullable()->constrained('core_district')->nullOnDelete();
                $table->foreignId('block_id')->nullable()->constrained('core_block')->nullOnDelete();
                $table->string('name');
                $table->string('city_code')->nullable();
                $table->string('pincode')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('core_city_village')) {
            Schema::create('core_city_village', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('state_id')->nullable();
                $table->unsignedBigInteger('district_id')->nullable();
                $table->unsignedBigInteger('block_id')->nullable();
                $table->string('name');
                $table->string('city_code')->nullable();
                $table->string('pincode')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('core_city_village');
        Schema::dropIfExists('core_city');
        Schema::dropIfExists('core_block');
        Schema::dropIfExists('core_district');
        Schema::dropIfExists('core_state');
        Schema::dropIfExists('core_country');
    }
};
