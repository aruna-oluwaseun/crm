<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('supplier_title')->nullable();
            $table->string('supplier_invoice_number')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
            $table->decimal('shipping_cost',10,2)->nullable();
            $table->decimal('shipping_vat',10,2)->nullable()->comment('Original shipping, additional goes on line item');
            $table->decimal('net_cost',10,2)->nullable();
            $table->decimal('discount_cost',10,2)->nullable();
            $table->decimal('vat_cost',10,2)->nullable();
            $table->decimal('gross_cost',10,2)->nullable();
            $table->decimal('paid',10,2)->nullable();
            $table->decimal('outstanding',10,2)->nullable();
            $table->foreignId('purchase_order_status_id')->nullable()->constrained('purchase_order_statuses')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('payment_term_id')->nullable()->constrained('payment_terms')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('purchase_orders');
    }
}
