<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderDispatchBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_dispatch_boxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_dispatch_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->comment('Box used')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('product_title')->nullable();
            $table->decimal('weight',10,4)->nullable();
            $table->decimal('weight_kg',10,4)->nullable();
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
        Schema::dropIfExists('sales_order_dispatch_boxes');
    }
}
