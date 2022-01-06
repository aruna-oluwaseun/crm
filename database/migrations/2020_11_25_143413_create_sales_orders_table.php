<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_ref')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');;
            $table->string('customer_ref')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company')->nullable();
            $table->string('order_number')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->text('billing_address_data')->nullable();
            $table->text('delivery_address_data')->nullable();
            $table->foreignId('shipping_option_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->decimal('shipping_cost',10,2)->nullable()->comment('Original shipping, additional goes on line item');
            $table->decimal('shipping_vat',10,2)->nullable()->comment('Original shipping, additional goes on line item');
            $table->decimal('shipping_gross',10,2)->nullable()->comment('Original shipping, additional goes on line item');
            $table->decimal('net_cost',10,2)->nullable()->comment('Net of sale items');
            $table->decimal('discount_cost',10,2)->nullable()->comment('Overall discount');
            $table->decimal('vat_cost',10,2)->nullable()->comment('Vat of sale items');
            $table->decimal('gross_cost',10,2)->nullable()->comment('Gross of sale items + shipping');
            //$table->tinyInteger('is_paid')->default(0)->comment('Puts stock into pending until dispatched'); // Build so works on qty fields
            $table->decimal('paid',10,2)->nullable();
            $table->decimal('outstanding',10,2)->nullable();
            $table->decimal('profit',10,2)->nullable();
            $table->decimal('cost_to_us',10,2)->nullable();
            $table->decimal('weight_kg',10,4)->nullable()->comment('Total weight of the order');
            $table->text('notes')->nullable();
            $table->date('quote_valid_until')->nullable();
            $table->tinyInteger('is_urgent')->default(0);
            $table->foreignId('packed_by_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('picked_by_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('packed_approved_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('sales_order_status_id')->nullable()->constrained('sales_order_statuses')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->dateTime('completed_at')->nullable();
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
        Schema::dropIfExists('sales_orders');
    }
}
