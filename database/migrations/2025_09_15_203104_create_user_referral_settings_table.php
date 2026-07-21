<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReferralSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('user_referral_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('referral_commission', 10, 2)->default(0.00);
            $table->decimal('referral_commission1', 10, 2)->default(0.00);
            $table->decimal('referral_commission2', 10, 2)->default(0.00);
            $table->decimal('referral_commission3', 10, 2)->default(0.00);
            $table->decimal('referral_commission4', 10, 2)->default(0.00);
            $table->decimal('referral_commission5', 10, 2)->default(0.00);
            $table->decimal('signup_bonus', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_referral_settings');
    }
}
