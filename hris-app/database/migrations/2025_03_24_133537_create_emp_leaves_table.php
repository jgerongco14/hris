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
        Schema::create('empLeaves', function (Blueprint $table) {
            $table->id();
            $table->string('empLeaveNo')->unique();
            $table->string('empID')->index();
            $table->date('empLeaveDateApplied');
            $table->string('leaveType');
            $table->date('empLeaveStartDate');
            $table->date('empLeaveEndDate');
            $table->string('empLeaveDescription');
            $table->string('empLeaveAttachment')->nullable();
            $table->timestamps();

            $table->foreign('empID')->references('empID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_Leaves');
    }
};
