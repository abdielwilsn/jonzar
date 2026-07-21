<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CryptoAccountsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('crypto_accounts')->insert([
            [
                'id' => 1,
                'user_id' => 17,
                'btc' => 0.99928,
                'eth' => 0.00175792,
                'ltc' => null,
                'xrp' => null,
                'link' => null,
                'bnb' => null,
                'aave' => null,
                'usdt' => 182.225,
                'xlm' => null,
                'bch' => null,
                'ada' => 0,
                'created_at' => '2021-10-31 12:25:53',
                'updated_at' => '2021-10-31 12:25:53',
            ],
            [
                'id' => 2,
                'user_id' => 56,
                'btc' => null,
                'eth' => null,
                'ltc' => null,
                'xrp' => null,
                'link' => null,
                'bnb' => null,
                'aave' => null,
                'usdt' => null,
                'xlm' => null,
                'bch' => null,
                'ada' => null,
                'created_at' => '2022-02-06 20:47:23',
                'updated_at' => '2022-02-06 20:47:23',
            ],
            [
                'id' => 3,
                'user_id' => 57,
                'btc' => null,
                'eth' => null,
                'ltc' => null,
                'xrp' => null,
                'link' => null,
                'bnb' => null,
                'aave' => null,
                'usdt' => null,
                'xlm' => null,
                'bch' => null,
                'ada' => null,
                'created_at' => '2022-02-19 01:43:24',
                'updated_at' => '2022-02-19 01:43:24',
            ],
        ]);
    }
}