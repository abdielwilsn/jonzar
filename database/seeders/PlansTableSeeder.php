<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('plans')->insert([
            [
                'id' => 6,
                'name' => 'Standard',
                'price' => '200',
                'min_price' => '200',
                'max_price' => '200000',
                'minr' => '400',
                'maxr' => '4000000000000',
                'gift' => '0',
                'expected_return' => null,
                'type' => 'Main',
                'increment_interval' => 'Hourly',
                'increment_type' => 'Percentage',
                'increment_amount' => '5',
                'expiration' => '12 Hours',
                'created_at' => '2021-10-25 13:25:08',
                'updated_at' => '2022-02-06 20:31:51',
            ],
        ]);
    }
}