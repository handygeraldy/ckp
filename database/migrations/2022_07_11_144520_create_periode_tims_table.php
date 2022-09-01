<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodeTimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periode_tims', function (Blueprint $table) {
            $table->id();
            $table->string("tahun", 4);
            $table->unsignedBigInteger('tim_id');
            $table->uuid('ketua_id')->nullable();
            $table->timestamps();

            $table->foreign('tim_id')->references('id')->on('tims');
            $table->foreign('ketua_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periode_tims');
    }
}
