<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CpTransactionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cp_transactions')->insert([
            [
                'id' => 1,
                'txn_id' => null,
                'item_name' => null,
                'Item_number' => null,
                'amount_paid' => null,
                'user_plan' => null,
                'user_id' => null,
                'user_tele_id' => null,
                'amount1' => null,
                'amount2' => null,
                'currency1' => null,
                'currency2' => null,
                'status' => null,
                'status_text' => null,
                'type' => null,
                'cp_p_key' => 'TYooMQauvdEDq54NiTphI7jx',
                'cp_pv_key' => '4eC39HqLyjWDarjtT1zdp7dc',
                'cp_m_id' => 'Merchid ID',
                'cp_ipn_secret' => 'jnndjnhdjdj',
                'cp_debug_email' => 'super@happ.com',
                'created_at' => '2021-03-11 12:46:45',
                'updated_at' => '2021-10-07 09:42:44',
            ],
        ]);
    }
}