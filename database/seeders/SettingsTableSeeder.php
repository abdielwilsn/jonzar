<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'site_name' => 'Starbiit',
                'description' => 'We are starbiit',
                'currency' => '$',
                's_currency' => 'USD',
                'capt_secret' => env('NOCAPTCHA_SECRET', ''),
                'capt_sitekey' => env('NOCAPTCHA_SITEKEY', ''),
                'payment_mode' => '123567',
                'location' => 'Local',
                's_s_k' => env('STRIPE_SECRET', ''),
                's_p_k' => env('STRIPE_KEY', ''),
                'pp_cs' => 'jijdjkdkdk',
                'pp_ci' => 'iidjdjdj',
                'keywords' => 'online trade, forex, cfd,',
                'site_title' => 'Welcome to Starbit',
                'site_address' => 'https://starbiit.com/',
                'logo' => 'giuNrOlogo 2.png1644161897',
                'favicon' => 'giuNrOfavicon.png1644161897',
                'trade_mode' => 'on',
                'google_translate' => 'on',
                'weekend_trade' => 'off',
                'contact_email' => 'starbiit247@gmail.com',
                'timezone' => 'UTC',
                'mail_server' => 'sendmail',
                'emailfrom' => 'hello@starbiit.com',
                'emailfromname' => 'JohnPraise',
                'smtp_host' => '',
                'smtp_port' => '2525',
                'smtp_encrypt' => 'tls',
                'smtp_user' => '',
                'smtp_password' => env('MAIL_PASSWORD', ''),
                'google_secret' => env('GOOGLE_CLIENT_SECRET', ''),
                'google_id' => env('GOOGLE_CLIENT_ID', ''),
                'google_redirect' => 'http://yoursite.com/auth/google/callback',
                'referral_commission' => '40',
                'referral_commission1' => '30',
                'referral_commission2' => '20',
                'referral_commission3' => '10',
                'referral_commission4' => '5',
                'referral_commission5' => '1',
                'signup_bonus' => '0',
                'tawk_to' => 'tawk to codess',
                'enable_2fa' => 'no',
                'enable_kyc' => 'yes',
                'enable_with' => 'true',
                'enable_verification' => 'false',
                'enable_social_login' => 'no',
                'withdrawal_option' => 'manual',
                'deposit_option' => 'manual',
                'dashboard_option' => 'light',
                'enable_annoc' => 'on',
                'subscription_service' => 'off',
                'captcha' => 'false',
                'commission_type' => null,
                'commission_fee' => null,
                'monthlyfee' => '30',
                'quarterlyfee' => '40',
                'yearlyfee' => '80',
                'newupdate' => 'Welcome to Starbiit',
                'created_at' => null,
                'updated_at' => '2022-02-12 05:45:36',
            ],
        ]);
    }
}