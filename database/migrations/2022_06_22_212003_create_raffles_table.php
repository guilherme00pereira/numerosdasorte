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
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->datetime('raffle_date');
            $table->string('prize', 4096)->nullable();
            $table->unsignedBigInteger('customer')->nullable();
            $table->string('chosen_number', 6)->nullable();
            $table->unsignedBigInteger('category');
            $table->timestamps();
            $table->foreign('customer')->references('id')->on('customers');
            $table->foreign('category')->references('id')->on('raffle_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raffles');
    }
};
