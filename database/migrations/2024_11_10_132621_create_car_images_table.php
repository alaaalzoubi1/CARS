<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_images', function (Blueprint $table) {
            $table->id(); // Primary Key (PK)
            $table->unsignedBigInteger('car_id'); // Foreign Key (FK)
            $table->string('image'); // To store the image path or URL
            $table->boolean('is_main')->default(false);
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraint
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_images');
    }
}
