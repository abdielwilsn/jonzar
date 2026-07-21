<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('contents')->insert([
            ['id' => 5, 'ref_key' => 'SMsJr1', 'title' => 'What our Customer says!', 'description' => 'Don\'t take our word for it, here\'s what some of our clients have to say about us', 'created_at' => '2020-08-22 11:13:00', 'updated_at' => '2021-10-27 09:59:35'],
            ['id' => 11, 'ref_key' => 'anvs8c', 'title' => 'About Us', 'description' => 'About us header', 'created_at' => '2020-08-22 11:32:29', 'updated_at' => '2021-10-27 10:21:22'],
            ['id' => 12, 'ref_key' => 'epJ4LI', 'title' => 'Who we are', 'description' => 'online trade is a solution for creating an investment management platform. It is suited for hedge or mutual fund managers and also Forex, stocks, bonds and cryptocurrency traders who are looking at runing pool trading system. Onlinetrader simplifies the investment, monitoring and management process. With a secure and compelling mobile-first design, together with a default front-end design, it takes few minutes to setup your own investment management or pool trading platform.', 'created_at' => '2020-08-22 11:33:32', 'updated_at' => '2021-10-27 10:24:01'],
            ['id' => 13, 'ref_key' => '5hbB6X', 'title' => 'Get Started', 'description' => 'How to get started ?', 'created_at' => '2020-08-22 11:33:55', 'updated_at' => '2021-10-27 10:25:09'],
            ['id' => 14, 'ref_key' => 'Zrhm3I', 'title' => 'Create an Account', 'description' => 'Create an account with us using your preffered email/username', 'created_at' => '2020-08-22 11:34:11', 'updated_at' => '2021-10-27 10:25:29'],
            ['id' => 15, 'ref_key' => 'yTKhlt', 'title' => 'Make a Deposit', 'description' => 'Make A deposit with any of your preffered currency', 'created_at' => '2020-08-22 11:34:26', 'updated_at' => '2021-10-27 10:25:52'],
            ['id' => 16, 'ref_key' => 'u0Ervr', 'title' => 'Start Trading/Investing', 'description' => 'Start trading with Indices commodities e.tc', 'created_at' => '2020-08-22 11:34:56', 'updated_at' => '2021-10-27 10:26:12'],
            ['id' => 23, 'ref_key' => 'vr6Xw0', 'title' => 'Our Investment Packages', 'description' => 'Choose how you want to invest with us', 'created_at' => '2020-08-22 11:37:43', 'updated_at' => '2021-10-27 09:58:51'],
            ['id' => 30, 'ref_key' => '52GPRA', 'title' => 'Address', 'description' => 'No 10 Mission Road, Nigeria', 'created_at' => '2020-08-22 11:40:19', 'updated_at' => '2020-08-22 11:40:19'],
            ['id' => 31, 'ref_key' => '0EXbji', 'title' => 'Phone Number', 'description' => '+234 9xxxxxxxx', 'created_at' => '2020-08-22 11:40:36', 'updated_at' => '2020-09-14 10:13:57'],
            ['id' => 32, 'ref_key' => 'HLgyaQ', 'title' => 'Email', 'description' => 'support@brynamics.xyz', 'created_at' => '2020-08-22 11:41:14', 'updated_at' => '2020-08-22 12:23:55'],
            ['id' => 35, 'ref_key' => 'Mnag31', 'title' => 'The Better Way to Trade & Invest', 'description' => 'Online Trade helps over 2 million customers achieve their financial goals by helping them trade and invest with ease', 'created_at' => '2021-10-27 09:42:23', 'updated_at' => '2021-10-27 09:42:23'],
            ['id' => 36, 'ref_key' => 'rXJ7JQ', 'title' => 'Trade Invest stock, and Bond', 'description' => 'Home page text', 'created_at' => '2021-10-27 09:45:17', 'updated_at' => '2021-10-27 09:45:17'],
            ['id' => 37, 'ref_key' => 'J23T0Y', 'title' => 'Security Comes First', 'description' => 'Security Comes first', 'created_at' => '2021-10-27 09:53:15', 'updated_at' => '2021-10-27 09:54:52'],
            ['id' => 38, 'ref_key' => '9HOR1z', 'title' => 'Security', 'description' => 'Online Trade uses the highest levels of Internet Security, and it is secured by 256 bits SSL security encryption to ensure that your information is completely protected from fraud.', 'created_at' => '2021-10-27 09:56:13', 'updated_at' => '2021-10-27 09:56:13'],
            ['id' => 39, 'ref_key' => '7DH2G9', 'title' => 'Two Factor Auth', 'description' => 'Two-factor authentication (2FA) by default on all Online Trade accounts, to securely protect you from unauthorised access and impersonation.', 'created_at' => '2021-10-27 09:56:26', 'updated_at' => '2021-10-27 09:56:26'],
            ['id' => 40, 'ref_key' => '5Vg32I', 'title' => 'Explore Our Services', 'description' => 'It’s our mission to provide you with a delightful and a successful trading experience!', 'created_at' => '2021-10-27 09:56:38', 'updated_at' => '2021-10-27 09:56:38'],
            ['id' => 41, 'ref_key' => 'Vg6Gy7', 'title' => 'Powerful Trading Platforms', 'description' => 'Online Trade offers multiple platform options to cover the needs of each type of trader and investors .', 'created_at' => '2021-10-27 09:56:53', 'updated_at' => '2021-10-27 09:56:53'],
            ['id' => 42, 'ref_key' => '1Sx1dl', 'title' => 'High leverage', 'description' => 'Chance to magnify your investment and really win big with super-low spreads to further up your profits', 'created_at' => '2021-10-27 09:57:06', 'updated_at' => '2021-10-27 09:57:06'],
            ['id' => 43, 'ref_key' => 'YYqKx3', 'title' => 'Fast execution', 'description' => 'Super-fast trading software, so you never suffer slippage.', 'created_at' => '2021-10-27 09:57:20', 'updated_at' => '2021-10-27 09:57:20'],
            ['id' => 44, 'ref_key' => 'yGg8xI', 'title' => 'Ultimate Security', 'description' => 'With advanced security systems, we keep your account always protected.', 'created_at' => '2021-10-27 09:57:35', 'updated_at' => '2021-10-27 09:57:35'],
            ['id' => 45, 'ref_key' => 'xEWMho', 'title' => '24/7 live chat Support', 'description' => 'Connect with our 24/7 support and Market Analyst on-demand.', 'created_at' => '2021-10-27 09:57:48', 'updated_at' => '2021-10-27 09:57:48'],
            ['id' => 46, 'ref_key' => '9SOtK1', 'title' => 'Always on the go? Mobile trading is easier than ever with Online Trade!', 'description' => 'Get your hands on our customized Trading Platform with the comfort of freely trading on the move, to experience truly liberating trading sessions.', 'created_at' => '2021-10-27 09:58:05', 'updated_at' => '2021-10-27 09:58:05'],
            ['id' => 47, 'ref_key' => 'wOS1ve', 'title' => 'Cryptocurrency', 'description' => 'Trade and invest Top Cryptocurrency', 'created_at' => '2021-10-27 09:59:07', 'updated_at' => '2021-10-27 09:59:07'],
            ['id' => 48, 'ref_key' => 'wuZlis', 'title' => 'Hello!, How can we help you?', 'description' => 'Hello!, How can we help you?', 'created_at' => '2021-10-27 10:32:12', 'updated_at' => '2021-10-27 10:32:12'],
            ['id' => 49, 'ref_key' => '1TYkw0', 'title' => 'Find the help you need', 'description' => 'Launch your campaign and benefit from our expertise on designing and managing conversion centered bootstrap4 html page.', 'created_at' => '2021-10-27 10:32:33', 'updated_at' => '2021-10-27 10:32:33'],
            ['id' => 50, 'ref_key' => 'rK6Yhn', 'title' => 'FAQs', 'description' => 'Due to its widespread use as filler text for layouts, non-readability is of great importance.', 'created_at' => '2021-10-27 10:32:49', 'updated_at' => '2021-10-27 10:32:49'],
            ['id' => 51, 'ref_key' => 'HBHBLo', 'title' => 'Guides / Support', 'description' => 'Due to its widespread use as filler text for layouts, non-readability is of great importance.', 'created_at' => '2021-10-27 10:33:03', 'updated_at' => '2021-10-27 10:33:03'],
            ['id' => 52, 'ref_key' => 'rCTDQh', 'title' => 'Support Request', 'description' => 'Due to its widespread use as filler text for layouts, non-readability is of great importance.', 'created_at' => '2021-10-27 10:33:14', 'updated_at' => '2021-10-27 10:33:14'],
            ['id' => 53, 'ref_key' => 'kMsswR', 'title' => 'Get Started', 'description' => 'Launch your campaign and benefit from our expertise on designing and managing conversion centered bootstrap4 html page.', 'created_at' => '2021-10-27 10:33:28', 'updated_at' => '2021-10-27 10:33:28'],
            ['id' => 54, 'ref_key' => 'EOUU7R', 'title' => 'Get in Touch !', 'description' => 'This is required when, for text is not yet available.', 'created_at' => '2021-10-27 10:33:56', 'updated_at' => '2021-10-27 10:33:56'],
            ['id' => 56, 'ref_key' => 'ROu4q6', 'title' => 'Contact Us', 'description' => 'Contact Us', 'created_at' => '2021-10-27 10:47:41', 'updated_at' => '2021-10-27 10:47:41'],
        ]);
    }
}