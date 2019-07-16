<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSteamItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_items', function (Blueprint $table) {
            $table->bigIncrements('id');

			$table->string('market_hash_name');
			$table->string('item_name')->nullable();
			$table->string('skin_name')->nullable();
			$table->string('condition')->nullable();
			$table->boolean('stattrak')->nullable();
			$table->integer('price');
			$table->text('icon_url');

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
        Schema::dropIfExists('steam_items');
    }
}
