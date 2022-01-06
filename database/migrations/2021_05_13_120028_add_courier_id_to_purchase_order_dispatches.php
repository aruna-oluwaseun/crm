<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourierIdToPurchaseOrderDispatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_dispatches', function (Blueprint $table) {
            $table->string('collected_by')->after('purchase_order_id')->nullable();
            $table->foreignId('courier_id')->after('company_address_data')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_dispatches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('courier_id');
            $table->dropColumn('collected_by');
        });
    }
}
