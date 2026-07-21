<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('txn_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // renamed from 'user'
            $table->string('uname')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('to_deduct', 15, 2)->nullable();
            $table->string('status')->nullable()->default('pending');
            $table->string('payment_mode')->nullable();

            // New columns for wallet withdrawals
            $table->string('wallet_address')->nullable();
            $table->string('network')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('withdrawals');
    }
}
