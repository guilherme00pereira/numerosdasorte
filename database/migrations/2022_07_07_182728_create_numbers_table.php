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
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer');
            $table->unsignedBigInteger('category');
            $table->unsignedBigInteger('order');
            $table->string('numbers', 4096);
            $table->date('expiration');
            $table->timestamps();
            $table->foreign('customer')->references('id')->on('customers');
            $table->foreign('category')->references('id')->on('raffle_categories');
            $table->foreign('order')->references('id')->on('orders');
            $table->index(['customer', 'category', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('numbers');
    }
};
