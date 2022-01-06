<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable();
            $table->decimal('percentage',12,2)->nullable();
            $table->decimal('net_adjustment',10,2)->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->tinyInteger('is_lifetime_discount')->default(0)->comment('Is for I.E installers only etc for every order, cart will look up against customer_discounts and product_types (categories allowed)');
            $table->enum('status', ['active','disabled','deleted'])->default('active');
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
        Schema::dropIfExists('discounts');
    }
}
