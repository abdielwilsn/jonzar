<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->insert([
            [
                'id' => 1,
                'firstName' => 'Admin',
                'lastName' => 'manager',
                'email' => 'admin@admin.com',
                'email_verified_at' => null,
                'password' => bcrypt('password'), 
                'token_2fa_expiry' => '2021-12-07 11:40:56',
                'enable_2fa' => 'disabled',
                'token_2fa' => '16632',
                'pass_2fa' => 'true',
                'phone' => '34444443',
                'dashboard_style' => 'dark',
                'remember_token' => null,
                'acnt_type_active' => 'active',
                'status' => 'active',
                'type' => 'Super Admin',
                'created_at' => '2021-03-10 12:55:53',
                'updated_at' => '2022-02-12 04:47:35',
            ],
            [
                'id' => 3,
                'firstName' => 'New',
                'lastName' => 'Admin',
                'email' => 'admin@remedy.com',
                'email_verified_at' => null,
                'password' => bcrypt('password'), 
                'token_2fa_expiry' => '2021-05-05 12:39:11',
                'enable_2fa' => 'disbaled',
                'token_2fa' => null,
                'pass_2fa' => null,
                'phone' => '2344',
                'dashboard_style' => 'light',
                'remember_token' => null,
                'acnt_type_active' => 'active',
                'status' => 'active',
                'type' => 'Admin',
                'created_at' => '2021-04-06 08:23:58',
                'updated_at' => '2022-02-12 04:31:21',
            ],
        ]);
    }
}