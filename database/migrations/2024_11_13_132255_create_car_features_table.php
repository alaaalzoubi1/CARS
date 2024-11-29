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
            $table->string('gear')->nullable();
            $table->string('engine')->nullable();
            $table->string('color')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->integer('luggage')->nullable();
            $table->boolean('sensors')->nullable();
            $table->boolean('bluetooth')->nullable();
            $table->boolean('gcc')->nullable();
            $table->boolean('camera')->nullable();
            $table->boolean('lcd')->nullable();
            $table->boolean('safety')->nullable();
            $table->boolean('radio')->nullable();
            $table->boolean('Mb3_CD')->nullable();
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
