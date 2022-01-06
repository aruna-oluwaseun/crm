<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('position_in_company')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            // role_id : create when roles table is built
            $table->string('contact_number')->nullable();
            $table->string('contact_number2')->nullable();
            $table->string('em_name')->nullable();
            $table->string('em_phone')->nullable();
            $table->string('em_email')->nullable();
            $table->integer('holiday_allowance')->default(0);
            $table->string('password');
            $table->rememberToken();
            $table->dateTime('last_login')->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('users');
    }
}
