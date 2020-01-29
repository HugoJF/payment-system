<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebhookHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webhook_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->mediumText('response');
            $table->unsignedInteger('status');
            $table->string('content_type');
            $table->string('error')->nullable();

            $table->string('order_id')->references('id')->on('orders');

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
        Schema::dropIfExists('webhook_histories');
    }
}
