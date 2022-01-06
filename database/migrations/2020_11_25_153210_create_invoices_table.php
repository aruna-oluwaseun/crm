<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('invoice_date')->nullable();
            $table->date('invoice_due')->nullable();
            $table->decimal('net_cost',10,2)->nullable()->comment('Net of sale item inc shipping');
            $table->decimal('discount_cost',10,2)->nullable()->comment('Overall discount');
            $table->decimal('vat_cost',10,2)->nullable()->comment('Vat of sale items inc shipping');
            $table->decimal('gross_cost',10,2)->nullable()->comment('Gross of sale items inc shipping');
            $table->foreignId('invoice_status_id')->nullable()->constrained('invoice_statuses')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->foreignId('shipping_invoice')->after('shipping_gross')->nullable()->constrained('invoices')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
