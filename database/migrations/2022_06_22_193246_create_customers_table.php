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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('external_code', 100)->unique();
            $table->string('cpf', 14);
            $table->string('name', 300);
            $table->string('email', 300);
            $table->date('birthdate');
            $table->string('phone', 22);
            $table->string('city', 200);
            $table->string('state', 50);
            $table->boolean('defaulter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};