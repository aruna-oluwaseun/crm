<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();
            $table->foreignId('sales_order_id')->comment('Online orders before they are invoiced')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('payment_method_id')->comment('For manual payments only')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('payment_ref')->nullable()->comment('Stripe or other vendor payment ref');
            $table->decimal('amount', 10,2)->nullable();
            $table->enum('status', ['pending','processing','complete','error'])->default('pending');
            $table->enum('type', ['income','payout','refund'])->default('income');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->dateTime('terms_agreed')->nullable();
            $table->tinyInteger('test_payment')->default(0);
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
        Schema::dropIfExists('payments');
    }
}
