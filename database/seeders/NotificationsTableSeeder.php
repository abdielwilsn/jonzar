<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('notifications')->insert([
            [
                'id' => 2,
                'user_id' => 9,
                'message' => 'This is a new mail Victory, kindly apprehiend',
                'created_at' => '2021-03-12 12:38:30',
                'updated_at' => '2021-03-12 12:38:30',
            ],
        ]);
    }
}