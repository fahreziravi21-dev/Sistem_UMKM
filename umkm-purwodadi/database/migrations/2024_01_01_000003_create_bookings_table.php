<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('kode_booking', 30)->unique();
            $table->unsignedBigInteger('id_pengguna');
            $table->string('nama_penerima', 100);
            $table->string('no_tlp_penerima', 15);
            $table->text('alamat_pengiriman');
            $table->unsignedBigInteger('total_harga')->default(0);
            $table->enum('status_booking', ['menunggu_bayar', 'dibayar', 'dikirim', 'selesai', 'batal'])->default('menunggu_bayar');
            $table->dateTime('tgl_booking')->useCurrent();
            $table->foreign('id_pengguna')->references('id_pengguna')->on('penggunas')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
