<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToPurchaseOrderDispatchItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_dispatch_items', function (Blueprint $table) {
            $table->foreignId('product_id')->after('purchase_order_item_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_dispatch_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
}
