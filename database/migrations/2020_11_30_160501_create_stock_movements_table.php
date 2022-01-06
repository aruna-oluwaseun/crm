<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('movement')->nullable();
            $table->decimal('unit_movement',10,4)->nullable();
            $table->foreignId('purchase_order_item_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null')->comment('Stock in'); //stock inbound
            $table->foreignId('sales_order_item_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null')->comment('Stock sold'); // stock outbound
            $table->tinyInteger('manually_added')->default(0);
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
        Schema::dropIfExists('stock_movements');
    }
}
