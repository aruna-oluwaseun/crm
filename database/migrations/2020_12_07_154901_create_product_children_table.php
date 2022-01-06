<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('product_title')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('weight',10,4)->nullable();
            $table->foreignId('unit_of_measure_id')->nullable()->constrained('unit_of_measures')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('weight_kg',10,4)->nullable();
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
        Schema::dropIfExists('product_children');
    }
}
