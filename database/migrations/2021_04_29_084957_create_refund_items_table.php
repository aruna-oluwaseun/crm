<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sales_order_item_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('title')->nullable();
            $table->integer('qty')->nullable();
            $table->dateTime('stock_updated')->nullable()->comment('On dispatch / collection update stock');
            $table->foreignId('vat_type_id')->nullable()->constrained('vat_types')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('vat_percentage',10,2)->nullable();
            $table->decimal('refund_item_cost',10,2)->nullable();
            $table->decimal('refund_net_cost',10,2)->nullable();
            $table->decimal('refund_vat_cost',10,2)->nullable();
            $table->decimal('refund_gross_cost',10,2)->nullable();
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
        Schema::dropIfExists('refund_items');
    }
}
