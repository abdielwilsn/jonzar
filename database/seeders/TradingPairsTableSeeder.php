<?php

namespace Database\Seeders;

use App\Models\TradingPair;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TradingPairsTableSeeder extends Seeder
{
    /**
     * Seed a set of popular trading pairs.
     *
     * `coingecko_id` matches CoinGecko so the app's price refresh can update
     * prices live; the seeded values give sensible defaults until then.
     */
    public function run()
    {
        $pairs = [
            ['coingecko_id' => 'bitcoin',      'base_symbol' => 'BTC',  'base_name' => 'Bitcoin',      'current_price' => 62450.00, 'price_change_24h' => 2.34,  'market_cap' => 1230000000000, 'volume_24h' => 28500000000],
            ['coingecko_id' => 'ethereum',     'base_symbol' => 'ETH',  'base_name' => 'Ethereum',     'current_price' => 3125.40,  'price_change_24h' => 1.86,  'market_cap' => 375000000000,  'volume_24h' => 14200000000],
            ['coingecko_id' => 'tether',       'base_symbol' => 'USDT', 'base_name' => 'Tether',       'current_price' => 1.00,     'price_change_24h' => 0.01,  'market_cap' => 112000000000,  'volume_24h' => 45000000000],
            ['coingecko_id' => 'binancecoin',  'base_symbol' => 'BNB',  'base_name' => 'BNB',          'current_price' => 585.20,   'price_change_24h' => 3.12,  'market_cap' => 86000000000,   'volume_24h' => 1800000000],
            ['coingecko_id' => 'solana',       'base_symbol' => 'SOL',  'base_name' => 'Solana',       'current_price' => 148.75,   'price_change_24h' => 5.41,  'market_cap' => 68000000000,   'volume_24h' => 3400000000],
            ['coingecko_id' => 'ripple',       'base_symbol' => 'XRP',  'base_name' => 'XRP',          'current_price' => 0.5230,   'price_change_24h' => -1.24, 'market_cap' => 29000000000,   'volume_24h' => 1200000000],
            ['coingecko_id' => 'cardano',      'base_symbol' => 'ADA',  'base_name' => 'Cardano',      'current_price' => 0.4485,   'price_change_24h' => -0.87, 'market_cap' => 15800000000,   'volume_24h' => 420000000],
            ['coingecko_id' => 'dogecoin',     'base_symbol' => 'DOGE', 'base_name' => 'Dogecoin',     'current_price' => 0.1235,   'price_change_24h' => 4.02,  'market_cap' => 17600000000,   'volume_24h' => 980000000],
            ['coingecko_id' => 'tron',         'base_symbol' => 'TRX',  'base_name' => 'TRON',         'current_price' => 0.1180,   'price_change_24h' => 0.65,  'market_cap' => 10200000000,   'volume_24h' => 310000000],
            ['coingecko_id' => 'litecoin',     'base_symbol' => 'LTC',  'base_name' => 'Litecoin',     'current_price' => 72.30,    'price_change_24h' => -2.10, 'market_cap' => 5400000000,    'volume_24h' => 380000000],
        ];

        foreach ($pairs as $index => $pair) {
            TradingPair::updateOrCreate(
                ['coingecko_id' => $pair['coingecko_id']],
                array_merge($pair, [
                    'quote_symbol' => 'USDT',
                    'base_icon_url' => 'https://assets.coincap.io/assets/icons/'.strtolower($pair['base_symbol']).'@2x.png',
                    'price_last_updated' => Carbon::now(),
                    'min_investment' => 50,
                    'max_investment' => 100000,
                    'min_return_percentage' => 5,
                    'max_return_percentage' => 25,
                    'investment_duration' => 24,
                    'max_investment_duration' => 168,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]),
            );
        }
    }
}
