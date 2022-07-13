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
            $table->id();
            $table->foreignId('ckp_id');
            $table->foreignId('tim_id');
            $table->string('name');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->foreignId('satuan_id');
            $table->integer('jml_target');
            $table->integer('jml_realisasi');
            $table->float('nilai_kegiatan', 5, 2);
            $table->foreignId('kredit_id');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('kegiatans');
    }
}
