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
            $table->unsignedBigInteger('projek_id');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('iku_id');
            $table->date('periode_awal')->nullable();
            $table->date('periode_akhir')->nullable();
<<<<<<< HEAD
            $table->boolean('tugas_luar')->default(false);
=======
            $table->string('satuan')->nullable();
            $table->string('target', 3)->nullable();
>>>>>>> 6885bee04f622a655064d7c0a4ebc9befdbfdfcc
            $table->enum('is_delete', ['1', '0'])->default('0');
            $table->timestamps();

            $table->foreign('projek_id')->references('id')->on('projeks');
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
