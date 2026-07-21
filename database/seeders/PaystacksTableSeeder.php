<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaystacksTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('paystacks')->insert([
            [
                'id' => 1,
                'created_at' => '2021-10-07 10:26:10',
                'updated_at' => '2021-12-07 08:24:59',
                'paystack_public_key' => null,
                'paystack_secret_key' => null,
                'paystack_url' => 'https://api.paystack.co',
                'paystack_email' => 'victrinhoj@gmail.com',
            ],
        ]);
    }
}