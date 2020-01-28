<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebhookColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('webhook_url')->nullable();
            $table->unsignedInteger('webhook_attempts')->default(0);
            $table->timestamp('webhook_attempted_at')->nullable();
            $table->timestamp('webhooked_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('webhook_url');
            $table->dropColumn('webhook_attempts');
            $table->dropColumn('webhook_attempted_at');
            $table->dropColumn('webhooked_at');
        });
    }
}
