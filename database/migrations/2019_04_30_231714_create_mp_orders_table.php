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

			$table->string('mp_preference_id');

			$table->unsignedInteger('mp_amount')->default(0);
			$table->unsignedInteger('mp_paid_amount')->default(0);

			$table->integer('mp_order_id')->unsigned()->nullable();
			$table->string('mp_order_status')->nullable();

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
