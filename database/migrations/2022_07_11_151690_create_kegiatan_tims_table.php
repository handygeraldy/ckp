<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanTimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_tims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tim_id');
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('tim_id')->references('id')->on('tims');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatan_tims');
    }
}
