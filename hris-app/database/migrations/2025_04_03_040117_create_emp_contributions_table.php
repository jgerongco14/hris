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
        Schema::create('empContributions', function (Blueprint $table) {
            $table->id();
            $table->string('empConNo')->unique();
            $table->string('empID')->index();
            $table->string('empContype');
            $table->string('empConAmount')->nullable(); 
            $table->string('employeerContribution')->nullable(); 
            $table->string('empPRNo')->nullable();
            $table->date('empConDate'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empContributions');
    }
};
