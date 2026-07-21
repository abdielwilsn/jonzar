<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->date('dob')->nullable();
            $table->string('cstatus')->nullable();
            $table->text('userupdate')->nullable();
            $table->string('assign_to')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('dashboard_style')->default('light');
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('acnt_type_active')->nullable();
            $table->string('btc_address')->nullable();
            $table->string('eth_address')->nullable();
            $table->string('ltc_address')->nullable();
            $table->string('plan')->nullable();
            $table->string('user_plan')->nullable();
            $table->float('account_bal')->nullable();
            $table->float('roi')->default(0.00);
            $table->float('bonus')->nullable();
            $table->float('ref_bonus')->nullable();
            $table->string('signup_bonus')->nullable();
            $table->string('auto_trade')->nullable();
            $table->integer('bonus_released')->default(0);
            $table->string('ref_by')->nullable();
            $table->string('ref_link')->nullable();
            $table->string('id_card')->nullable();
            $table->string('passport')->nullable();
            $table->string('account_verify')->nullable();
            $table->dateTime('entered_at')->nullable();
            $table->dateTime('activated_at')->nullable();
            $table->dateTime('last_growth')->nullable();
            $table->string('status', 25)->default('active');
            $table->string('trade_mode')->default('on');
            $table->string('act_session')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->unsignedBigInteger('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->string('withdrawotp')->nullable();
            $table->string('sendotpemail')->default('Yes');
            $table->string('sendroiemail')->default('Yes');
            $table->string('sendpromoemail')->default('Yes');
            $table->string('sendinvplanemail')->default('Yes');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}