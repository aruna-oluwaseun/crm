<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPurchaseOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->foreignId('unit_of_measure_id')->after('product_title')->nullable()->constrained('unit_of_measures')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('weight',10,4)->after('unit_of_measure_id')->nullable();
            $table->decimal('weight_kg',10,4)->after('weight')->nullable();
            $table->decimal('item_cost',10,2)->after('vat_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unit_of_measure_id');
            $table->dropColumn(['weight','weight_kg','item_cost']);
        });
    }
}
