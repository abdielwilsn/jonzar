<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstName', 255);
            $table->string('lastName', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->dateTime('token_2fa_expiry')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('enable_2fa', 255)->default('disabled');
            $table->string('token_2fa', 255)->nullable();
            $table->string('pass_2fa', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('dashboard_style', 255)->default('dark');
            $table->string('remember_token', 255)->nullable();
            $table->string('acnt_type_active', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->timestamps();
        });


        Schema::create('paystacks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('paystack_public_key')->nullable();
            $table->text('paystack_secret_key')->nullable();
            $table->string('paystack_url', 255)->nullable();
            $table->string('paystack_email', 255)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
                Schema::dropIfExists('paystacks');

    }
}