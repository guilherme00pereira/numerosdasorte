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
        Schema::create('raffle_cursomer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raffle');
            $table->unsignedBigInteger('customer');
            $table->string('numbers', 1024);
            $table->foreign('raffle')->references('id')->on('raffles');
            $table->foreign('customer')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
