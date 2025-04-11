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
        Schema::create('empAssignments', function (Blueprint $table) {
            $table->id();
            $table->string('empAssNo')->unique();
            $table->string('empID')->index();
            $table->string('positionID')->index();
            $table->date('empAssAppointedDate');
            $table->date('empAssEndDate');
            $table->string('officeCode',100)->nullable()->index();
            $table->string('departmentCode',100)->nullable()->index();
            $table->timestamps();

            $table->foreign('empID')->references('empID')->on('employees');
            $table->foreign('positionID')->references('positionID')->on('positions');
            $table->foreign('officeCode')->references('officeCode')->on('offices');
            $table->foreign('departmentCode')->references('departmentCode')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empAssignments');
    }
};
