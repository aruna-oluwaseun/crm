<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('build_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('product_title')->nullable();
            $table->float('qty')->nullable();
            $table->foreignId('unit_of_measure_id')->nullable()->constrained('unit_of_measures')->onUpdate('cascade')->onDelete('set null');
            $table->float('qty_used')->nullable();
            $table->tinyInteger('stock_updated')->default(0);
            $table->timestamps();
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('build_product_id')->after('sales_order_item_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null'); // raw stock
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('build_products');
    }
}
