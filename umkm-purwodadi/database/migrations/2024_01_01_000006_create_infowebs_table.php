<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfowebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infowebs', function (Blueprint $table) {
            $table->id('id_infoweb');
            $table->string('nama_web', 100)->default('UMKM Dusun 01 Purwodadi');
            $table->string('telp', 15);
            $table->text('alamat');
            $table->string('email', 100);
            $table->string('bank_toko', 50)->default('Bank BRI');
            $table->string('no_rek_toko', 50);
            $table->string('atas_nama_toko', 100);
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
        Schema::dropIfExists('infowebs');
    }
}
