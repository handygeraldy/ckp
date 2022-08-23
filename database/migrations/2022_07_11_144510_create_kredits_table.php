<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();     
            $table->unsignedBigInteger('jafung_id');
            $table->string('tingkat', 10);
            $table->string('kode_perka', 9);
            $table->string('kode_unsur', 3);
            $table->string('unsur', 40);
            $table->text('name');
            $table->string('kegiatan', 256);
            $table->string('satuan', 50);
            $table->text('bukti_fisik');
            $table->decimal('angka_kredit', 6, 4);
            $table->string('pelaksana_kegiatan', 50);
            $table->text('keterangan');
            $table->decimal('pelaksana', 6, 4);
            $table->decimal('pelaksana_lanjutan', 6, 4);
            $table->decimal('penyelia', 6, 4);
            $table->decimal('pertama', 6, 4);
            $table->decimal('muda', 6, 4);
            $table->decimal('madya', 6, 4);
            $table->decimal('utama', 6, 4);
            $table->enum('is_delete', ['1', '0'])->default('0');
            $table->timestamps();

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
        Schema::dropIfExists('kredits');
    }
}
