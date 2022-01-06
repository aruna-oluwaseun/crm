<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVatReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vat_returns', function (Blueprint $table) {
            $table->id();
            $table->string('vat_number')->nullable();
            $table->string('period_key');
            $table->decimal('vat_due_sales',10,2)->default('0');
            $table->decimal('vat_due_acquisitions',10,2)->default('0');
            $table->decimal('total_vat_due',10,2)->default('0');
            $table->decimal('vat_reclaimed_curr_period',10,2)->default('0');
            $table->decimal('net_vat_due',10,2)->default('0');
            $table->decimal('total_value_sales_ex_vat',10,2)->default('0');
            $table->decimal('total_value_purchases_ex_vat',10,2)->default('0');
            $table->decimal('total_value_good_supplied_ex_vat',10,2)->default('0');
            $table->decimal('total_acquisitions_ex_vat',10,2)->default('0');
            $table->tinyInteger('finalised')->default(0);
            $table->string('correlation_id')->nullable()->comment('for operation tracking');
            $table->string('receipt_id')->nullable()->comment('reference for submission');
            $table->string('receipt_timestamp')->nullable()->comment('Timestamp from hmrc ISO8601');
            $table->string('processing_date')->nullable()->comment('The time the message was processed by HMRC');
            $table->string('form_bundle_number')->nullable()->comment('unique number represents the form bundle');
            $table->string('payment_indicator')->nullable()->comment('DD if the netVatDue value is a debit and HMRC holds a DD Ins for the client. Is BANK if the netVatDue value is a credit and HMRC holds the client bank data.');
            $table->string('charge_ref_number')->nullable()->comment('The charge reference number is returned, only if the netVatDue value is a debit.');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });

        // Store foreign keys on other tables
        Schema::table('invoices', function(Blueprint $table) {
            $table->foreignId('vat_return_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('purchase_orders', function(Blueprint $table) {
            $table->foreignId('vat_return_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('refunds', function(Blueprint $table) {
            $table->foreignId('vat_return_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('expenses', function(Blueprint $table) {
            $table->foreignId('vat_return_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vat_returns');

        Schema::table('invoices', function(Blueprint $table) {
            $table->dropForeign('invoices_vat_return_id_foreign');
            $table->dropColumn('vat_return_id');
        });
        Schema::table('purchase_orders', function(Blueprint $table) {
            $table->dropForeign('purchase_orders_vat_return_id_foreign');
            $table->dropColumn('vat_return_id');
        });
        Schema::table('refunds', function(Blueprint $table) {
            $table->dropForeign('refunds_vat_return_id_foreign');
            $table->dropColumn('vat_return_id');
        });
    }
}
