<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCkpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ckps', function (Blueprint $table) {
            $table->id();
            $table->string('tahun',4);
            $table->string('bulan',2);
            $table->foreignId('satker_id');
            $table->foreignId('user_id');
            $table->integer('jml_kegiatan')->nullable();
            $table->float('avg_kuantitas', 5, 2)->nullable();
            $table->float('avg_kualitas', 5, 2)->nullable();
            $table->float('nilai_akhir', 5, 2)->nullable();
            $table->float('angka_kredit', 5, 2)->nullable();
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
        Schema::dropIfExists('ckps');
    }
}
