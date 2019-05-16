<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSteamOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

			$table->longText('encoded_items')->nullable();

			$table->bigInteger('tradeoffer_id')->unsigned()->nullable();
			$table->integer('tradeoffer_state')->unsigned()->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steam_orders');
    }
}
