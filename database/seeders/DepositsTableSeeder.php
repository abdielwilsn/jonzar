<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('deposits')->insert([
            ['id' => 49, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => '9PmdNpauth.png1644081803', 'created_at' => '2022-02-05 22:23:23', 'updated_at' => '2022-02-05 23:10:02'],
            ['id' => 50, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'i2W2k1Solflex_Logo1.png1644081849', 'created_at' => '2022-02-05 22:24:09', 'updated_at' => '2022-02-05 23:10:13'],
            ['id' => 51, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => '0IMnkTSolflex_Logo1.png1644081983', 'created_at' => '2022-02-05 22:26:23', 'updated_at' => '2022-02-05 23:09:51'],
            ['id' => 52, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'zaOYQFpp.jpg1644082480', 'created_at' => '2022-02-05 22:34:40', 'updated_at' => '2022-02-05 23:09:23'],
            ['id' => 53, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'fIRs9Npp.jpg1644082608', 'created_at' => '2022-02-05 22:36:48', 'updated_at' => '2022-02-05 23:09:13'],
            ['id' => 54, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Pending', 'proof' => 'xxyCWtpp.jpg1644082827', 'created_at' => '2022-02-05 22:40:27', 'updated_at' => '2022-02-05 22:40:27'],
            ['id' => 55, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'TX969ypp.jpg1644082842', 'created_at' => '2022-02-05 22:40:42', 'updated_at' => '2022-02-05 23:09:04'],
            ['id' => 56, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => '0x1um1pp.jpg1644083043', 'created_at' => '2022-02-05 22:44:03', 'updated_at' => '2022-02-05 23:08:44'],
            ['id' => 57, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'aEALMO2587474703a2f2f692e60966.png1644083310', 'created_at' => '2022-02-05 22:48:30', 'updated_at' => '2022-02-05 23:08:34'],
            ['id' => 58, 'txn_id' => null, 'user' => 55, 'amount' => '1000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'ok3BUK2587474703a2f2f692e60966.png1644083723', 'created_at' => '2022-02-05 22:55:23', 'updated_at' => '2022-02-05 23:08:25'],
            ['id' => 59, 'txn_id' => null, 'user' => 56, 'amount' => '10000', 'payment_mode' => 'Bitcoin', 'plan' => null, 'status' => 'Processed', 'proof' => 'MVHogRlogo.png1644623423', 'created_at' => '2022-02-12 04:50:23', 'updated_at' => '2022-02-12 05:41:31'],
            ['id' => 60, 'txn_id' => null, 'user' => 56, 'amount' => '10000', 'payment_mode' => 'Ethereum', 'plan' => null, 'status' => 'Processed', 'proof' => 'UcqSM2jp.png1644626100', 'created_at' => '2022-02-12 05:35:00', 'updated_at' => '2022-02-12 05:40:41'],
        ]);
    }
}