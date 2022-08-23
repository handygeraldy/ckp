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
            $table->unsignedBigInteger('jafung_id');
            $table->string('name', 100);
            $table->string('kolom_kredit', 30)->nullable();

            $table->foreign('jafung_id')->references('id')->on('jafungs');
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
