<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingOptionPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_option_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_option_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->mediumText('countries')->nullable();
            $table->decimal('cost_to_us',10,2);
            $table->decimal('net_cost',10,2);
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
        Schema::dropIfExists('shipping_option_prices');
    }
}
