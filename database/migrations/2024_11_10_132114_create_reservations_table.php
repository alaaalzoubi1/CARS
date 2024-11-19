<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // Primary Key (PK)
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id');
            $table->boolean('with_driver');
            $table->date('start');
            $table->date('end');
            $table->enum('status', ['pending', 'canceled', 'approved']);
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('reservations');
    }
}
