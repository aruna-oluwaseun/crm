<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->string('product_title')->nullable();
            $table->foreignId('sales_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->integer('qty')->nullable();
            $table->dateTime('stock_updated')->nullable()->comment('On dispatch / collection update stock');
            $table->foreignId('unit_of_measure_id')->nullable()->constrained('unit_of_measures')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('weight',10,4)->nullable();
            $table->decimal('weight_kg',10,4)->nullable();
            $table->foreignId('vat_type_id')->nullable()->constrained('vat_types')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('vat_percentage',10,2)->nullable();
            $table->decimal('item_cost',10,2)->nullable();
            $table->decimal('net_cost',10,2)->nullable();
            $table->decimal('discount_cost',10,2)->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('vat_cost',10,2)->nullable();
            $table->decimal('gross_cost',10,2)->nullable();
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('profit',10,2)->nullable();
            $table->decimal('cost_to_us',10,2)->nullable();
            $table->tinyInteger('paid_deposit')->default(0);
            $table->decimal('amount_outstanding',10,2)->nullable();
            $table->date('amount_outstanding_due')->nullable();
            $table->tinyInteger('is_paid')->default(0)->comment('Puts stock into pending until dispatched'); // Build so works on qty fields
            $table->date('training_date')->nullable();
            $table->string('training_course')->nullable();
            $table->foreignId('training_date_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->text('attributes')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('is_additional_shipping')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**at
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
}
