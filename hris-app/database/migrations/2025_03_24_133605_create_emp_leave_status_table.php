<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empLeaveStatus', function (Blueprint $table) {
            $table->id();
            $table->string('empLSNo');
            $table->unsignedBigInteger('empLeaveNo')->index();
            $table->string('empLSOffice');
            $table->unsignedBigInteger('empID')->index();
            $table->string('empLSStatus');
            $table->string('empLSRemarks');
            $table->timestamps();

            $table->foreign('empLeaveNo')->references('empLeaveNo')->on('empLeaves');
            $table->foreign('empID')->references('empID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_leave_status');
    }
};
