<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('code')->nullable();
            $table->string('commodity_code')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('net_cost',10,2)->nullable();
            $table->decimal('vat_cost',10,2)->nullable();
            $table->decimal('gross_cost',10,2)->nullable();
            $table->decimal('sale_net_cost',10,2)->nullable();
            $table->decimal('sale_vat_cost',10,2)->nullable();
            $table->decimal('sale_gross_cost',10,2)->nullable();
            $table->dateTime('sale_expire')->nullable();
            $table->decimal('weight',10,4)->nullable();
            $table->decimal('electric_parts_weight',10,4)->nullable();
            $table->foreignId('unit_of_measure_id')->nullable()->constrained('unit_of_measures')->onUpdate('cascade')->onDelete('set null');
            $table->decimal('weight_kg',10,4)->nullable();
            $table->tinyInteger('builds_by_unit')->default(0)->comment('For products that have the weight attached but are added to builds by unit');
            $table->text('keywords')->nullable();
            $table->tinyInteger('is_available_online')->default(0);
            $table->tinyInteger('is_manufactured')->default(0);
            $table->tinyInteger('is_discountable')->default(0);
            $table->tinyInteger('is_training')->default(0);
            $table->tinyInteger('is_assessment')->default(0);
            $table->tinyInteger('is_free_shipping')->default(0);
            $table->tinyInteger('is_packaged')->default(0);
            $table->tinyInteger('deposit_allowed')->default(0);
            $table->tinyInteger('is_product_option')->default(0)->comment('Can be selected in the product options section, wont be shown in product lists');
            $table->decimal('deposit_net_cost',10,2)->nullable();
            $table->decimal('deposit_vat_cost',10,2)->nullable();
            $table->decimal('deposit_gross_cost',10,2)->nullable();
            $table->integer('assembly_minutes')->nullable();
            $table->foreignId('product_type_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('vat_type_id')->nullable()->constrained('vat_types')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('lead_time_id')->nullable()->constrained('lead_times')->onUpdate('cascade')->onDelete('set null');
            $table->enum('status', ['active','disabled','deleted','draft'])->default('active');
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
        Schema::dropIfExists('products');
    }
}
