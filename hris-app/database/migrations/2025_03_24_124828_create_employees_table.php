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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('empID')->unique();
            $table->string('photo')->nullable();
            $table->string('empPrefix')->nullable();
            $table->string('empSuffix')->nullable();
            $table->string('empFname')->nullable();
            $table->string('empMname')->nullable();
            $table->string('empLname')->nullable();
            $table->string('empGender')->nullable();
            $table->string('empBirthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('barangay')->nullable();
            $table->string('empSSSNum')->nullable();
            $table->string('empTinNum')->nullable();
            $table->string('empPagIbigNum')->nullable();
            $table->string('empCivilStatus')->nullable();
            $table->string('empBloodType')->nullable();
            $table->string('empContactNo')->nullable();
            $table->string('empRVMRetirementNo')->nullable();
            $table->string('empBPIATMAccountNo')->nullable();
            $table->string('empDateHired')->nullable();
            $table->string('empDateResigned')->nullable();
            $table->string('empPersonelStatus')->nullable();
            $table->string('empEmployeerName')->nullable();
            $table->string('empEmployeerAddress', 500)->nullable();
            $table->string('empFatherName',100)->nullable();
            $table->string('empMotherName',100)->nullable();
            $table->string('empSpouseName',100)->nullable();
            $table->string('empSpouseBdate', 100)->nullable();
            $table->string('empChildrenName', 500)->nullable();
            $table->string('empChildrenBdate', 500)->nullable();
            $table->string('empEmergencyContactName', 100)->nullable();
            $table->string('empEmergencyContactAddress', 500)->nullable();
            $table->string('empEmergencyContactNo')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
