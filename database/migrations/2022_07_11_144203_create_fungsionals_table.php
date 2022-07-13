<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFungsionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fungsionals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('kolom_kredit', 30)->nullable();
            $table->enum('is_delete', ['1', '0'])->default('0');
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
        Schema::dropIfExists('fungsionals');
    }
}
