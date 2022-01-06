<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingVatTypeIdToPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('shipping_vat_type_id')->after('shipping_cost')->nullable()->constrained('vat_types')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('shipping_gross',10,2)->after('shipping_vat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipping_vat_type_id');
            $table->dropColumn('shipping_vat_type_id');
        });
    }
}
