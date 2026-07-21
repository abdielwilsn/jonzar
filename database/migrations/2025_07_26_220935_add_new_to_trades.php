<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trades', function (Blueprint $table) {
            // Remove old currency column and add new pair-based columns
            $table->dropColumn('currency');
            
            // Add new columns for trading pairs
            $table->string('pair_base')->after('user_id')->comment('Base currency (e.g., btc)');
            $table->string('quote_currency')->default('usdt')->after('pair_base')->comment('Quote currency (e.g., usdt)');
            
            // Add order type and additional trading fields
            $table->enum('order_type', ['market', 'limit', 'stop'])->default('market')->after('type');
            
            // Add fee tracking
            $table->decimal('fee', 15, 8)->default(0)->after('volume');
            
            // Add stop loss and take profit
            $table->decimal('stop_loss', 20, 8)->nullable()->after('fee');
            $table->decimal('take_profit', 20, 8)->nullable()->after('stop_loss');
            
            // Add realized P/L (separate from the existing profit_loss)
            $table->decimal('realized_pl', 15, 8)->default(0)->after('take_profit')->comment('Realized profit/loss for closed positions');
            
            // Add execution and closing timestamps
            $table->timestamp('executed_at')->nullable()->after('created_at')->comment('When the order was actually executed');
            $table->timestamp('closed_at')->nullable()->after('executed_at')->comment('When the position was closed');
            
            // Update status enum to include pending
            $table->dropColumn('status');
        });
        
        // Add the updated status column with new enum values
        Schema::table('trades', function (Blueprint $table) {
            $table->enum('status', ['pending', 'open', 'closed'])->default('open')->after('take_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trades', function (Blueprint $table) {
            // Restore original currency column
            $table->string('currency')->after('user_id');
            
            // Remove new columns
            $table->dropColumn([
                'pair_base',
                'quote_currency', 
                'order_type',
                'fee',
                'stop_loss',
                'take_profit',
                'realized_pl',
                'executed_at',
                'closed_at'
            ]);
            
            // Restore original status enum
            $table->dropColumn('status');
        });
        
        Schema::table('trades', function (Blueprint $table) {
            $table->enum('status', ['open', 'closed'])->default('open');
        });
    }
};