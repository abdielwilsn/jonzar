<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sessions')->insert([
            [
                'id' => 'GkkuVAewLzoyYZovkwHkvsxGtNVlXD8BsL5jDpQi',
                'user_id' => null,
                'ip_address' => '197.210.55.115',
                'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicmFxQ1RkekFnUHNlWHFOVk82eGxVdGtYMDIyQlBNZjF5d3A3S05QTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vaHlpcC5tZXRhZnhjcnlwdG8uY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
                'last_activity' => 1645612700,
            ],
            [
                'id' => 'ljG4yUY93JGWgPmQCwqDgJmKL1kT0mN8efaapjsS',
                'user_id' => null,
                'ip_address' => '168.151.138.72',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib2lOY0h4WEk1emMwVkdmVE93ZnN0aHBjRDhNQWF1c2V0dWZQakNQYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vaW52ZXN0LnRyYWRlYmVzdGZ4Lm5ldCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',
                'last_activity' => 1645613135,
            ],
            [
                'id' => 'noH7fc1ZowUwsnm3SYI7Q4W1qkpvsldHAwBMrrV5',
                'user_id' => null,
                'ip_address' => '119.13.203.165',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWFvNVI0RVVVcEJEMFJNTzVnVGFDZ2U3YUFuRzJ0TlFodFpnYktaYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vd3d3LmludmVzdC50cmFkZWJlc3RmeC5uZXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',
                'last_activity' => 1645613149,
            ],
        ]);
    }
}