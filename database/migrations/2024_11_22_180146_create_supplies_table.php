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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity');
            $table->foreignId('category_id')->constrained();
            $table->string('color_code')->nullable();
            $table->string('color_shade')->nullable();
            $table->string('size')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('online_supplier_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
