<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->string('start_type')->nullable();
            $table->string('end_type')->nullable();
            $table->decimal('time_off', 10,2)->nullable();
            $table->text('description')->nullable();
            $table->text('reason')->nullable();
            $table->enum('type',['holiday','sick','authorised-absence'])->default('holiday');
            $table->enum('status',['pending','approved','declined'])->default('pending');
            $table->dateTime('approved')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('holidays');
    }
}
