<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_features', function (Blueprint $table) {
            $table->id();
            $table->string('gear');
            $table->string('engine');
            $table->string('color');
            $table->integer('seats');
            $table->integer('doors');
            $table->integer('luggage');
            $table->boolean('sensors');
            $table->boolean('bluetooth');
            $table->boolean('gcc');
            $table->boolean('camera');
            $table->boolean('lcd');
            $table->boolean('safety');
            $table->boolean('radio');
            $table->boolean('Mb3_CD');
            $table->unsignedBigInteger('car_id');
            $table->timestamps();

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
        Schema::dropIfExists('car_features');
    }
}
