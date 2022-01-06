<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('line1')->nullable();
            $table->string('title')->nullable();
            $table->string('line2')->nullable();
            $table->string('line3')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->enum('status', ['active','deleted'])->default('active');
            $table->tinyInteger('default_billing_address')->default(0);
            $table->tinyInteger('default_shipping_address')->default(0);
            $table->timestamps();
        });

        // Add foreign keys to customers table
        Schema::table('customers', function(Blueprint $table) {
            $table->foreignId('billing_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('delivery_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('head_office_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
