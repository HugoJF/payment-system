<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->bigIncrements('id');

			$table->string('public_id');

			$table->string('reason');
			$table->string('return_url');
			$table->string('cancel_url');

			$table->string('payer_steam_id')->nullable();
			$table->string('payer_tradelink')->nullable();

			$table->unsignedInteger('preset_amount');

			$table->unsignedInteger('unit_price');
			$table->unsignedInteger('unit_price_limit');
			$table->float('discount_per_unit');

			$table->unsignedInteger('min_units');
			$table->unsignedInteger('max_units');

			$table->string('orderable_type')->nullable();
			$table->unsignedBigInteger('orderable_id')->nullable();
			$table->index(['orderable_type', 'orderable_id'], 'orderable_index');

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
		Schema::dropIfExists('orders');
	}
}
