<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAmountColumnsFromMpOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mp_orders', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mp_orders', function (Blueprint $table) {
            $table->unsignedInteger('amount')->default(0);
            $table->unsignedInteger('paid_amount')->default(0);
        });
    }
}
