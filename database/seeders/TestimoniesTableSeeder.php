<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimoniesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('testimonies')->insert([
            [
                'id' => 2,
                'ref_key' => 'mZVhqO',
                'position' => 'Principal',
                'name' => 'Sarah Ona',
                'what_is_said' => 'I love this platform',
                'picture' => '5EJXRd02.jpg1635329727',
                'created_at' => '2021-04-01 15:00:56',
                'updated_at' => '2021-10-27 09:16:30',
            ],
            [
                'id' => 3,
                'ref_key' => 'EPVy3D',
                'position' => 'Investor',
                'name' => 'Mike Dinne',
                'what_is_said' => 'I have invested and its all good, please try it out guys',
                'picture' => 'SIu0JZ01.jpg1635329714',
                'created_at' => '2021-10-27 09:16:14',
                'updated_at' => '2021-10-27 09:17:31',
            ],
        ]);
    }
}