<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            // ADD OPTIONS LINK
            $table->integer('stock')->nullable()->comment('Int level for sellable items');
            $table->decimal('unit_level_stock',10,4)->nullable()->comment('Unit level stock for Raw products where stock is all unit based i.e Grams or KG');
            $table->integer('min_order_qty')->nullable();
            $table->decimal('unit_level_min_order_qty', 10,4)->nullable();
            $table->decimal('unit_level_product_order_at', 10,4)->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
