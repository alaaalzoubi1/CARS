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
             $table->string('trademark')->nullable();
             $table->string('model')->nullable();
             $table->string('delivery')->nullable();
             $table->text('details')->nullable();
             $table->unsignedBigInteger('rent_id');
             $table->foreign('rent_id')->references('id')->on('rents')->onDelete('cascade');
             $table->string('insurance')->nullable();
             $table->string('KMs')->nullable();
             $table->string('deposit')->nullable();
             $table->string('min_age')->nullable();
             $table->unsignedBigInteger('category_id')->nullable();
             $table->boolean('is_hidden')->default(false);
             $table->year('date_of_manufacture')->nullable();
             $table->year('registration_date')->nullable();
             $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
             $table->timestamps();
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
