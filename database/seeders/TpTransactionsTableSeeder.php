<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TpTransactionsTableSeeder extends Seeder
{
    public function run()
    {
        $transactions = [
            [
                'id' => 12,
                'plan' => 'From Account unused deposit to capital',
                'user' => 17,
                'amount' => '10',
                'type' => 'Transfer',
                'created_at' => '2021-04-22 10:15:19',
                'updated_at' => '2021-04-22 10:15:19',
            ],
            [
                'id' => 13,
                'plan' => 'From Profit to Unused Deposit',
                'user' => 17,
                'amount' => '10',
                'type' => 'Transfer',
                'created_at' => '2021-04-22 10:18:35',
                'updated_at' => '2021-04-22 10:18:35',
            ],
            // Add remaining records (up to id 82) from the provided SQL
        ];

        DB::table('tp__transactions')->insert($transactions);
    }
}