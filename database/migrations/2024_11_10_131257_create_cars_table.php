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
        Schema::create('cars', function (Blueprint $table) {
             $table->id();
             $table->string('trademark');
             $table->date('date');
             $table->text('details');
             $table->unsignedBigInteger('rent_id');
             $table->foreign('rent_id')->references('id')->on('rents')->onDelete('cascade');
             $table->text('explication');
             $table->unsignedBigInteger('category_id');
             $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); $table->timestamps(); // Adds created_at and updated_at columns });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
