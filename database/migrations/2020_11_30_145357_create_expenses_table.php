<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_title');
            $table->decimal('net_cost',10,2)->nullable();
            $table->decimal('vat_cost',10,2)->nullable();
            $table->decimal('gross_cost',10,2)->nullable();
            $table->foreignId('payment_method')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->enum('status', ['pending','processing','complete'])->default('pending');
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
        Schema::dropIfExists('expenses');
    }
}
