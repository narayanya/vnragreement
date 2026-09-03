<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fermer_land', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('fid')->comment('farmers.fid FK');
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->unsignedInteger('tahsil_id')->nullable();
            $table->unsignedInteger('village_id')->nullable();
            $table->decimal('sowing_area', 10, 3)->nullable()->comment('Area in Acres');
            $table->string('khasra_no', 50)->nullable()->comment('Khasra / Survey No.');
            $table->string('cr_by', 10)->nullable();
            $table->date('cr_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fermer_land');
    }
};
