<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tim_id');
            $table->uuid('anggota_id');
            // constraint duplicate tim_id - anggota_id

            $table->foreign('tim_id')->references('id')->on('tims');
            $table->foreign('anggota_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tims');
    }
}
