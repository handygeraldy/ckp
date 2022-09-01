<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanTimUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan__tim__users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kegiatan_tim_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('kegiatan_tim_id')->references('id')->on('kegiatan_tims');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatan__tim__users');
    }
}
