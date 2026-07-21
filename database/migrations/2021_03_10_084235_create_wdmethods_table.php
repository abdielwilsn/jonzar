<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWdmethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wdmethods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('minimum')->nullable();
            $table->string('maximum')->nullable();
            $table->string('charges_fixed')->nullable();
            // new stuff
            
            $table->string('charges_type')->nullable();
            $table->string('methodtype')->nullable();
            $table->string('img_url')->nullable();
            // $table->string('img_url')->nullable();
            $table->string('bankname')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('barcode')->nullable();
            $table->string('wallet_address')->nullable();
            $table->string('network')->nullable();






            $table->string('charges_amount')->nullable();
            // new stuff
            $table->string('charges_percentage')->nullable();
            $table->string('duration')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('wdmethods');
    }
}
