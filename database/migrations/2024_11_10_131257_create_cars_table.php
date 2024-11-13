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
             $table->string('model');
             $table->string('delivery');
             $table->text('details');
             $table->unsignedBigInteger('rent_id');
             $table->foreign('rent_id')->references('id')->on('rents')->onDelete('cascade');
             $table->string('insurance');
             $table->string('KMs');
             $table->string('deposit');
             $table->string('min_age');
             $table->unsignedBigInteger('category_id');
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
