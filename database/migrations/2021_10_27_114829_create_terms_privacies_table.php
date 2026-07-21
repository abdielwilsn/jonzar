<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsPrivaciesTable extends Migration
{
    public function up()
    {
        Schema::create('terms_privacies', function (Blueprint $table) {
            $table->id();
            $table->text('terms')->nullable();
            // $table->text()
            $table->text('privacy')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terms_privacies');
    }
}