<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('nip', 18)->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();            
            $table->unsignedBigInteger('satker_id');
            $table->unsignedBigInteger('tim_utama')->nullable();
            $table->unsignedBigInteger('golongan_id');
            $table->unsignedBigInteger('fungsional_id');
            $table->string('password');
            $table->string('ttd');
            $table->enum('is_delete', ['1', '0'])->default('0');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('satker_id')->references('id')->on('satkers');
            $table->foreign('tim_utama')->references('id')->on('tims');
            $table->foreign('golongan_id')->references('id')->on('golongans');
            $table->foreign('fungsional_id')->references('id')->on('fungsionals');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
