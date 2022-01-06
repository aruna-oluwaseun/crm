<?php

use App\Models\FranchiseAddress;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable();
            $table->string('national_holiday')->nullable();
            $table->tinyInteger('global_owner')->default(0)->comment('Identifies the global owner of all franchises so they can see franchise lists and reports etc');
            $table->string('contact_number')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->unique();
            $table->foreignId('payment_term_id')->nullable()->constrained('payment_terms')->onUpdate('cascade')->onDelete('set null');
            $table->string('vat_number')->nullable();
            $table->string('company_number')->nullable();
            $table->enum('status', ['active','suspended','deleted'])->default('active');
            $table->text('notes')->nullable()->comment('Suspended notes etc');
            $table->foreignId('owned_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });

        // Add franchise link
        Schema::table('users', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('customers', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('products', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('roles', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('categories', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('expenses', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('purchase_orders', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('suppliers', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('sales_orders', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('invoices', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('shipping_types', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('shipping_options', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('product_types', function(Blueprint $table) {
            $table->foreignId('franchise_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
        });

    }

    /**
     * Get customer addresses
     */
    public function addresses()
    {
        return $this->hasMany(FranchiseAddressAlias::class);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchises');
    }
}
