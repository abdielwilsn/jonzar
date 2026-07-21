<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsPrivaciesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('terms_privacies')->insert([
            [
                'id' => 1,
                'terms' => 'Sample terms of service content.',
                'privacy' => 'Sample privacy policy content.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}