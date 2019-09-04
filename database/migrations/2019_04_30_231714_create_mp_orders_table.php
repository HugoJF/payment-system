<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMPOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_orders', function (Blueprint $table) {
            $table->bigIncrements('id');

			$table->string('preference_id');

			$table->unsignedInteger('amount')->default(0);
			$table->unsignedInteger('paid_amount')->default(0);

			$table->integer('order_id')->unsigned()->nullable();

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
        Schema::dropIfExists('mp_orders');
    }
}
