<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('currency', 255)->nullable();
            $table->string('s_currency', 255)->nullable();
            $table->string('capt_secret', 255)->nullable();
            $table->string('capt_sitekey', 255)->nullable();
            $table->string('payment_mode', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('s_s_k', 255)->nullable();
            $table->string('s_p_k', 255)->nullable();
            $table->string('pp_cs', 255)->nullable();
            $table->string('pp_ci', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->string('site_title', 255)->nullable();
            $table->string('site_address', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('trade_mode', 255)->nullable();
            $table->string('google_translate', 255)->nullable();
            $table->string('weekend_trade', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('timezone', 255)->nullable();
            $table->string('mail_server', 20)->nullable();
            $table->string('emailfrom', 255)->nullable();
            $table->string('emailfromname', 255)->nullable();
            $table->string('smtp_host', 255)->nullable();
            $table->string('smtp_port', 255)->nullable();
            $table->string('smtp_encrypt', 255)->nullable();
            $table->string('smtp_user', 255)->nullable();
            $table->string('smtp_password', 255)->nullable();
            $table->string('google_secret', 255)->nullable();
            $table->string('google_id', 255)->nullable();
            $table->string('google_redirect', 255)->nullable();
            $table->string('referral_commission', 255)->nullable();
            $table->string('referral_commission1', 255)->nullable();
            $table->string('referral_commission2', 255)->nullable();
            $table->string('referral_commission3', 255)->nullable();
            $table->string('referral_commission4', 255)->nullable();
            $table->string('referral_commission5', 255)->nullable();
            $table->string('signup_bonus', 255)->nullable();
            $table->longText('tawk_to')->nullable();
            $table->string('enable_2fa', 255)->default('no');
            $table->string('enable_kyc', 255)->default('no');
            $table->string('enable_with', 255)->nullable();
            $table->string('enable_verification', 255)->default('true');
            $table->string('enable_social_login', 255)->nullable();
            $table->string('withdrawal_option', 255)->default('auto');
            $table->string('deposit_option', 255)->nullable();
            $table->string('dashboard_option', 255)->nullable();
            $table->string('enable_annoc', 255)->nullable();
            $table->text('subscription_service')->nullable();
            $table->string('captcha', 255)->nullable();
            $table->string('commission_type', 255)->nullable();
            $table->string('commission_fee', 255)->nullable();
            $table->string('monthlyfee', 255)->nullable();
            $table->string('quarterlyfee', 255)->nullable();
            $table->string('yearlyfee', 255)->nullable();
            $table->string('newupdate', 255)->nullable();
            $table->timestamps();
        });


        Schema::create('settings_conts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('use_crypto_feature', 20)->default('true');
            $table->float('fee')->default(0);
            $table->string('btc', 20)->default('enabled');
            $table->string('eth', 20)->default('enabled');
            $table->string('ltc', 20)->default('enabled');
            $table->string('link', 20)->default('enabled');
            $table->string('bnb', 255)->default('enabled');
            $table->string('aave', 250)->default('enabled');
            $table->string('usdt', 250)->default('enabled');
            $table->string('bch', 255)->default('enabled');
            $table->string('xlm', 255)->default('enabled');
            $table->string('xrp', 255)->default('enabled');
            $table->string('ada', 255)->default('enabled');
            $table->integer('currency_rate')->nullable();
            $table->integer('minamt')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
                Schema::dropIfExists('settings_conts');

    }
}