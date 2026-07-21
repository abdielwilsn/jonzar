<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMt4DetailsTable extends Migration
{
    public function up()
    {
        Schema::create('mt4_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->nullable();
            $table->string('mt4_id', 255)->nullable();
            $table->string('mt4_password', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('account_type', 255)->nullable();
            $table->string('currency', 255)->nullable();
            $table->string('leverage', 255)->nullable();
            $table->string('server', 255)->nullable();
            $table->string('options', 255)->nullable();
            $table->string('duration', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mt4_details');
    }
}