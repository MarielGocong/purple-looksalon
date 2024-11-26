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
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('pay_with_cash')->comment('1 for cash payment, 0 for GCash');
            $table->string('proof_of_payment')->nullable()->comment('Path to the proof of GCash payment');
            $table->string('reference_number', 4)->nullable()->comment('Last 4 digits of GCash reference number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
