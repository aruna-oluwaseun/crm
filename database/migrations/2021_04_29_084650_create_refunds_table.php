<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company')->nullable();
            $table->text('reason')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('refund_ref')->nullable()->comment('Stripe or other vendor refund ref');
            $table->decimal('net_cost',10,2)->nullable()->comment('Net of sale items');
            $table->decimal('vat_cost',10,2)->nullable()->comment('Vat of sale items');
            $table->decimal('gross_cost',10,2)->nullable()->comment('Gross of sale items + shipping');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','processing','complete','error'])->default('pending');
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
        Schema::dropIfExists('refunds');
    }
}
