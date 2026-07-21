<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            AdminsTableSeeder::class,
            ContentsTableSeeder::class,
            CpTransactionsTableSeeder::class,
            CryptoAccountsTableSeeder::class,
            // DepositsTableSeeder::class,
            FaqsTableSeeder::class,
            ImagesTableSeeder::class,
            NotificationsTableSeeder::class,
            PaystacksTableSeeder::class,
            PlansTableSeeder::class,
            SessionsTableSeeder::class,
            SettingsTableSeeder::class,
            // SettingsContsTableSeeder::class,
            TermsPrivaciesTableSeeder::class,
            TestimoniesTableSeeder::class,
            TpTransactionsTableSeeder::class,
            UserPlansTableSeeder::class,
            ActivitiesTableSeeder::class,
            TradingPairsTableSeeder::class,
        ]);
    }
}