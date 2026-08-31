<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('core_variety')) {
            Schema::create('core_variety', function (Blueprint $table) {
                $table->string('name')->nullable();
                $table->string('code', 11)->nullable();
                $table->string('remark')->nullable();
                $table->integer('status')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('core_variety');
    }
};
