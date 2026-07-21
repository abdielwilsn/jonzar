<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('images')->insert([
            ['id' => 8, 'ref_key' => 'DPd1Kn', 'title' => 'Testimonial 1', 'description' => 'Testimonial 1', 'img_path' => 'SIu0JZ01.jpg1635329714', 'created_at' => '2020-08-23 12:24:52', 'updated_at' => '2021-10-27 10:15:14'],
            ['id' => 9, 'ref_key' => 'ZqCgDz', 'title' => 'Testimonial 2', 'description' => 'Testimonial 2', 'img_path' => '5EJXRd02.jpg1635329727', 'created_at' => '2020-08-23 12:25:07', 'updated_at' => '2021-10-27 10:15:27'],
            ['id' => 14, 'ref_key' => 'b9158B', 'title' => 'Home Image', 'description' => 'The image at the home page', 'img_path' => 'b9158Babout.jpg', 'created_at' => '2021-10-27 09:48:42', 'updated_at' => '2021-10-27 09:48:42'],
            ['id' => 15, 'ref_key' => 'iAwfKe', 'title' => 'About image', 'description' => 'The image in the about page', 'img_path' => 'iAwfKeabout.png', 'created_at' => '2021-10-27 10:22:20', 'updated_at' => '2021-10-27 10:22:20'],
        ]);
    }
}