<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('txn_id', 255)->nullable();
            $table->integer('user')->nullable();
            $table->string('amount', 255)->nullable();
            $table->string('payment_mode', 255)->nullable();
            $table->integer('plan')->nullable();
            $table->string('status', 255)->nullable();
            $table->string('proof', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}