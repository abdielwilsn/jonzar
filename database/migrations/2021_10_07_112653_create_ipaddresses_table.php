<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpaddressesTable extends Migration
{
    public function up()
    {
        Schema::create('ipaddresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ipaddress', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ipaddresses');
    }
}