<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user')->nullable();
            $table->string('ip_address', 255)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('device', 255)->nullable();
            $table->string('browser', 255)->nullable();
            $table->string('os', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}