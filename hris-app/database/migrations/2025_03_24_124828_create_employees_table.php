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
            $table->id('empID');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
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
