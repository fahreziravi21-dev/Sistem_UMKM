<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_booking')->unique();
            $table->string('no_rekening', 30);
            $table->string('nama_rekening', 100);
            $table->string('bank_pengirim', 50);
            $table->unsignedBigInteger('nominal');
            $table->dateTime('tgl_pembayaran')->useCurrent();
            $table->string('bukti', 255);
            $table->enum('status_konfirmasi', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->foreign('id_booking')->references('id_booking')->on('bookings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
}
