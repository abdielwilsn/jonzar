<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPlansTable extends Migration
{
    public function up()
    {
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('plan')->nullable();
            $table->integer('user')->nullable();
            $table->string('amount')->nullable();
            $table->string('active')->nullable();
            $table->string('inv_duration')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->dateTime('activated_at')->nullable();
            $table->dateTime('last_growth')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_plans');
    }
}