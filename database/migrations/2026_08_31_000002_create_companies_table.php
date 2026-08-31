<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('company_code')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->text('address')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->string('source')->default('custom');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
