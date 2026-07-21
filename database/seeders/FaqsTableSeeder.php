<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('faqs')->insert([
            [
                'id' => 1,
                'ref_key' => '8yZ6FC',
                'question' => 'How can i withdraw',
                'answer' => 'This is how to withdraw',
                'created_at' => '2021-03-11 14:31:42',
                'updated_at' => '2021-03-11 14:31:59',
            ],
        ]);
    }
}