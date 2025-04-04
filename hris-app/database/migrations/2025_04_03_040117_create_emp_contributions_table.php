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
            $table->string('payRefNo')->nullable();
            $table->string('empID')->index();
            $table->string('empContype');
            $table->decimal('empConAmount');
            $table->string('empConDate', 7); // Format: YYYY-MM
            $table->string('empConRemarks')->nullable();
        
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
