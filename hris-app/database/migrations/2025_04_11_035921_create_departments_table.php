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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('departmentCode',100)->unique();
            $table->string('departmentName',100);
            $table->string('departmentHead',100)->nullable();
            $table->string('programCode',100)->nullable();
            $table->timestamps();


            $table->foreign('departmentHead')->references('empID')->on('employees');
            $table->foreign('programCode')->references('programCode')->on('programs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
