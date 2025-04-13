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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('empID')->index();
            $table->string('empTrainName', 100);
            $table->string('empTrainDescription', 500)->nullable();
            $table->string('empTrainFromDate',100)->nullable();
            $table->string('empTrainToDate',100)->nullable();
            $table->string('empTrainLocation', 500)->nullable();
            $table->string('empTrainConductedBy',200)->nullable();
            $table->string('empTrainCertificate', 500)->nullable();
            $table->timestamps();

            $table->foreign('empID')->references('empID')->on('employees')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
