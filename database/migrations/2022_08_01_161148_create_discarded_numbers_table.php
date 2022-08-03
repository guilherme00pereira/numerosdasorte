<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discarded_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raffle_id');
            $table->unsignedBigInteger('number_id');
            $table->foreign('raffle_id')->references('id')->on('raffles');
            $table->foreign('number_id')->references('id')->on('numbers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.

     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discarded_numbers');
    }
};
