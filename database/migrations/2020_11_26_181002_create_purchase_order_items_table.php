<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('supplier_code')->nullable();
            $table->string('product_title')->nullable();
            $table->integer('qty')->nullable();
            $table->foreignId('vat_type_id')->nullable()->constrained('vat_types')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('vat_percentage',10,2)->nullable();
            $table->decimal('net_cost',10,2)->nullable();
            $table->decimal('discount_cost',10,2)->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('vat_cost',10,2)->nullable();
            $table->decimal('gross_cost',10,2)->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('purchase_order_items');
    }
}
