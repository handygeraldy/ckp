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
            $table->uuid("id")->primary();
            $table->unsignedBigInteger('tim_id');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('iku_id');
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
            $table->timestamps();

            $table->foreign('tim_id')->references('id')->on('periode_tims');
            $table->foreign('iku_id')->references('id')->on('ind_kinerjas');
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
