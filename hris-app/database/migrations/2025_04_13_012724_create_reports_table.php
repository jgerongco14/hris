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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('empID')->index();
            $table->string('status')->default('resigned');
            $table->string('semester')->nullable();
            $table->string('year')->nullable();
            $table->string('empTurnOverRate')->nullable();
            $table->string('reason')->nullable();
            $table->string('attachments')->nullable();
            $table->timestamps();

            $table->foreign('empID')->references('empID')->on('employees')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
