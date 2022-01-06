<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable();
            $table->foreignId('billing_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('head_office_id')->nullable()->constrained('addresses')->onUpdate('cascade')->onDelete('set null');
            $table->string('contact_number')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->unique();
            $table->foreignId('payment_term_id')->nullable()->constrained('payment_terms')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('vat_type_id')->nullable()->constrained('vat_types')->onUpdate('cascade')->onDelete('set null');
            $table->enum('status', ['active','deleted'])->default('active');
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
        Schema::dropIfExists('suppliers');
    }
}
