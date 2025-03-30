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
        Schema::create('empAttendances', function (Blueprint $table) {
            $table->id();
            $table->string('empAttNo')->unique();
            $table->string('empID')->index();
            $table->integer('empAttID');
            $table->date('empAttDate');
            $table->time('empAttTimeIn')->nullable();
            $table->string('empAttBreakOut')->nullable();
            $table->string('empAttBreakIn')->nullable();
            $table->time('empAttTimeOut')->nullable();
            $table->string('empOvertime')->nullable();
            $table->string('empAttUndertime')->nullable();
            $table->string('empAttRemarks')->nullable();
            $table->timestamps();

            $table->foreign('empID')->references('empID')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empAttendances');
    }
};
