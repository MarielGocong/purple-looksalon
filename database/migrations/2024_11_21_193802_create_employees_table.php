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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('age');
            $table->string('image')->nullable();
            $table->string('phone_number',11);
            $table->string('email')->unique();
            $table->date('birthday')->nullable();
            $table->string('address')->nullable();
            $table->date('date_started');
            $table->foreignId('job_category_id')->constrained();
            $table->json('working_days')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_hidden')->default(false);
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
