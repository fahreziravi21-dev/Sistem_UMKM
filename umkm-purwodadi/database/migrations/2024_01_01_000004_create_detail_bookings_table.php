<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_bookings', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_booking');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedInteger('jumlah')->default(1);
            $table->unsignedBigInteger('subtotal');
            $table->foreign('id_booking')->references('id_booking')->on('bookings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_bookings');
    }
}
