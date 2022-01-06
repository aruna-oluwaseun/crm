<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSalesOrderDispatchBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_order_dispatch_boxes', function (Blueprint $table) {
            $table->integer('qty')->after('product_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_order_dispatch_boxes', function (Blueprint $table) {
            $table->dropColumn(['qty']);
        });
    }
}
