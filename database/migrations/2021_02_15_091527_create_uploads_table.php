<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('file');
            $table->string('original_file_name')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('created_by_customer')->nullable()->constrained('customers')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('updated_by_customer')->nullable()->constrained('customers')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::dropIfExists('uploads');*/
    }
}
