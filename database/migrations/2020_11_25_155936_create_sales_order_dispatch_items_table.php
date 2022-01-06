<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderDispatchItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_dispatch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_dispatch_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sales_order_item_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('product_title')->nullable();
            $table->integer('qty')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('sales_order_dispatch_items');
    }
}
