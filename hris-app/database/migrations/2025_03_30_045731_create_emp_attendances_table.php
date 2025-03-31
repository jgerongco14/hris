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
            $table->string('empID', 30)->index();
            $table->string('empAttID', 30);
            $table->date('empAttDate');
            $table->string('empAttTimeIn', 20)->nullable();
            $table->string('empAttBreakOut', 20)->nullable();
            $table->string('empAttBreakIn', 20)->nullable();
            $table->string('empAttTimeOut', 20)->nullable();
            $table->string('empAttRemarks', 255)->nullable();
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
