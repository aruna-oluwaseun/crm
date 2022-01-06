<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->tinyInteger('is_collection')->default(0);
            $table->string('collected_by')->nullable();
            $table->foreignId('delivery_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->text('delivery_address_data')->nullable()->comment('Delivery address json in-case address id is deleted or one off when dispatching');
            $table->foreignId('courier_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('courier_title')->nullable();
            $table->string('tracking_number')->nullable();
            $table->foreignId('loaded_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->date('expected_delivery_date')->nullable()->comment('Collection fields too');
            $table->dateTime('actual_delivery_date')->nullable()->comment('Collection fields too');
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
        Schema::dropIfExists('sales_order_dispatches');
    }
}
