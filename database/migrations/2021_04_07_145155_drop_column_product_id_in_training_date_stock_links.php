<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnProductIdInTrainingDateStockLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_date_stock_links', function (Blueprint $table) {
            $table->dropForeign('training_date_stock_links_product_id_foreign');
            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_date_stock_links', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
