<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributeTitleToAttributeProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attribute_product', function (Blueprint $table) {
            $table->string('attribute_title')->nullable()->after('value_id')->comment('Override the product title that is set by value id');
            $table->text('exclude_value_attributes')->nullable()->after('attribute_title')->comment('What attributes linked to the value id are you going to exclude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_product', function (Blueprint $table) {
            $table->dropColumn('attribute_title');
            $table->dropColumn('exclude_value_attributes');
        });
    }
}
