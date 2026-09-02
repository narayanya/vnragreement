<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->unsignedInteger('core_state_id')->nullable()->after('village_id');
            $table->unsignedInteger('core_district_id')->nullable()->after('core_state_id');
            $table->unsignedInteger('core_village_id')->nullable()->after('core_district_id');
        });
    }

    public function down(): void
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn(['core_state_id', 'core_district_id', 'core_village_id']);
        });
    }
};
