<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role
            $table->unsignedBigInteger('role_id')->nullable()->after('email');

            // Employee linkage
            $table->string('employee_id', 50)->nullable()->after('role_id');
            $table->string('emp_code', 30)->nullable()->after('employee_id');
            $table->string('mobile_number', 15)->nullable()->after('emp_code');
            $table->string('emp_reporting', 50)->nullable()->after('mobile_number');

            // Flags
            $table->tinyInteger('status')->default(1)->after('emp_reporting');          // 1=Active, 0=Inactive
            $table->tinyInteger('is_external')->default(0)->after('status');            // 0=Internal, 1=External
            $table->tinyInteger('can_download_pdf')->default(0)->after('is_external');  // 0=No, 1=Yes

            // Foreign key
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'role_id', 'employee_id', 'emp_code', 'mobile_number',
                'emp_reporting', 'status', 'is_external', 'can_download_pdf',
            ]);
        });
    }
};
