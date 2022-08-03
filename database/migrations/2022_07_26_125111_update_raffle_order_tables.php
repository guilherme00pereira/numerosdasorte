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
        Schema::table('raffles', function (Blueprint $table) {
            $table->dropColumn('chosen_number');
            $table->unsignedBigInteger('number')->nullable();
            $table->foreign('number')->references('id')->on('numbers');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('num_items');
            $table->unique('order_id');
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
