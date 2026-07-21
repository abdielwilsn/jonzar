<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trading_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('coingecko_id')->unique(); // e.g., 'bitcoin', 'ethereum'
            $table->string('base_symbol'); // e.g., 'BTC', 'ETH'
            $table->string('base_name'); // e.g., 'Bitcoin', 'Ethereum'
            $table->string('quote_symbol')->default('USDT'); // e.g., 'USDT', 'USD'
            $table->string('base_icon_url')->nullable(); // Cryptocurrency icon URL

            // Price data (updated from CoinGecko API)
            $table->decimal('current_price', 20, 8)->default(0);
            $table->decimal('price_change_24h', 10, 4)->default(0); // Percentage change
            $table->decimal('market_cap', 20, 2)->nullable();
            $table->bigInteger('volume_24h')->nullable();
            $table->timestamp('price_last_updated')->nullable();

            // Investment parameters (set by admin)
            $table->decimal('min_investment', 15, 2); // Minimum investment amount
            $table->decimal('max_investment', 15, 2); // Maximum investment amount
            $table->decimal('min_return_percentage', 8, 4); // Minimum return percentage
            $table->decimal('max_return_percentage', 8, 4); // Maximum return percentage
            $table->integer('investment_duration'); // Duration in days
            $table->integer('max_investment_duration')->nullable();

            // Status and configuration
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('coingecko_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trading_pairs');
    }
}
