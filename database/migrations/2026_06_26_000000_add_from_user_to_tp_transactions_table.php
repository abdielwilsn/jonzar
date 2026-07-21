<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromUserToTpTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tp__transactions', function (Blueprint $table) {
            // The downline user whose deposit/trade generated this bonus.
            $table->integer('from_user')->nullable()->after('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tp__transactions', function (Blueprint $table) {
            $table->dropColumn('from_user');
        });
    }
}
