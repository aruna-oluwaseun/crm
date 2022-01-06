<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_address_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->text('company_address_data')->nullable()->comment('Delivery address json in-case address id is deleted');
            $table->string('courier_title')->nullable();
            $table->string('tracking_number')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();
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
        Schema::dropIfExists('purchase_order_dispatches');
    }
}
