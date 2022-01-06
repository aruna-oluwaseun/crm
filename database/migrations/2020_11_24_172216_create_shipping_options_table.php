<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable();
            $table->tinyInteger('is_free_shipping')->default(0);
            $table->foreignId('shipping_type_id')->nullable()->constrained('shipping_types')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('weight_from',10,4)->nullable();
            $table->decimal('weight_to',10,4)->nullable();
            $table->enum('weight_operator',['>=','>','<','<='])->nullable();
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
        Schema::dropIfExists('shipping_options');
    }
}
