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
			$table->string('id')->unique();

			$table->string('reason');
			$table->string('return_url');
			$table->string('cancel_url');

			$table->string('product_name_singular')->nullable();
			$table->string('product_name_plural')->nullable();

			$table->string('avatar')->nullable();

			$table->string('payer_steam_id')->nullable();
			$table->string('payer_tradelink')->nullable();

			$table->unsignedInteger('preset_amount');
			$table->unsignedInteger('paid_amount')->default(0);

			$table->unsignedInteger('unit_price');
			$table->unsignedInteger('unit_price_limit');
			$table->float('discount_per_unit');

			$table->unsignedInteger('min_units');
			$table->unsignedInteger('max_units');

			$table->unsignedInteger('recheck_attempts')->default(0);

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
