<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('price', 255)->nullable();
            $table->string('min_price', 255)->nullable();
            $table->string('max_price', 255)->nullable();
            $table->string('minr', 255)->nullable();
            $table->string('maxr', 255)->nullable();
            $table->string('gift', 255)->nullable();
            $table->string('expected_return', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('increment_interval', 255)->nullable();
            $table->string('increment_type', 255)->nullable();
            $table->string('increment_amount', 255)->nullable();
            $table->string('expiration', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}