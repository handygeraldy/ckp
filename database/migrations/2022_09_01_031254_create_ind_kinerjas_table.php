<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndKinerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ind_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->unsignedBigInteger('tujuan_id');
            $table->string('sasaran');
            $table->string('iku');
            $table->string('satuan');
            $table->string('target', 3);

            $table->foreign('tujuan_id')->references('id')->on('tujuans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ind_kinerjas');
    }
}
