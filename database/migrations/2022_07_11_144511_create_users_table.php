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
            $table->id();
            $table->char('nip', 18)->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();            
            $table->foreignId('satker_id');
            $table->foreignId('golongan_id');
            $table->foreignId('fungsional_id');
            $table->string('password');
            $table->enum('is_delete', ['1', '0'])->default('0');
            $table->foreignId('role_id');
            $table->timestamps();
            // $table->softDeletes();
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
