<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatesTrainingIdStockToTrainingDateStockLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_date_stock_links', function (Blueprint $table) {
            $table->foreignId('updates_training_id_stock')->after('training_date_id')->constrained('training_dates','id')->onDelete('cascade');
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
            $table->dropColumn('updates_training_id_stock');
        });
    }
}
