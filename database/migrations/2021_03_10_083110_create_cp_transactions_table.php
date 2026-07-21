<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('cp_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('txn_id', 255)->nullable();
            $table->string('item_name', 255)->nullable();
            $table->string('Item_number', 255)->nullable();
            $table->string('amount_paid', 255)->nullable();
            $table->string('user_plan', 255)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('user_tele_id')->nullable();
            $table->string('amount1', 255)->nullable();
            $table->string('amount2', 255)->nullable();
            $table->string('currency1', 255)->nullable();
            $table->string('currency2', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('status_text', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('cp_p_key', 255)->nullable();
            $table->string('cp_pv_key', 255)->nullable();
            $table->string('cp_m_id', 255)->nullable();
            $table->string('cp_ipn_secret', 255)->nullable();
            $table->string('cp_debug_email', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('crypto_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->float('btc')->nullable();
            $table->float('eth')->nullable();
            $table->float('ltc')->nullable();
            $table->float('xrp')->nullable();
            $table->float('link')->nullable();
            $table->float('bnb')->nullable();
            $table->float('aave')->nullable();
            $table->float('usdt')->nullable();
            $table->float('xlm')->nullable();
            $table->float('bch')->nullable();
            $table->float('ada')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cp_transactions');

                Schema::dropIfExists('crypto_accounts');

    }
}