<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ckp_id');
            $table->unsignedBigInteger('tim_id');
            $table->uuid('kegiatan_tim_id')->nullable();
            $table->string('name');
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->unsignedBigInteger('satuan_id');
            $table->integer('jml_target');
            $table->integer('jml_realisasi');
            $table->float('nilai_kegiatan', 5, 2)->nullable();
            $table->unsignedBigInteger('kredit_id')->nullable();
            $table->float('angka_kredit', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('ckp_id')->references('id')->on('ckps');
            $table->foreign('tim_id')->references('id')->on('tims');
            $table->foreign('kegiatan_tim_id')->references('id')->on('kegiatan_tims');
            $table->foreign('satuan_id')->references('id')->on('satuans');
            $table->foreign('kredit_id')->references('id')->on('kredits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatans');
    }
}
