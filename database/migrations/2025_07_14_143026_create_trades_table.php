<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency'); // e.g., BTC
            $table->enum('type', ['buy', 'sell']);
            $table->decimal('entry_price', 20, 8);
            $table->decimal('amount', 20, 8); // in USD
            $table->decimal('volume', 20, 8); // in BTC/ETH/etc
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->decimal('exit_price', 20, 8)->nullable();
            $table->decimal('profit_loss', 20, 8)->nullable();
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
        Schema::dropIfExists('trades');
    }
}
