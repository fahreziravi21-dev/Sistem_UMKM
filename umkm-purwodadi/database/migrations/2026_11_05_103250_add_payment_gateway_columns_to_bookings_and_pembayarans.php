<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPaymentGatewayColumnsToBookingsAndPembayarans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'snap_token')) {
                $table->string('snap_token', 255)->nullable()->after('status_booking');
            }
            if (!Schema::hasColumn('bookings', 'metode_pembayaran')) {
                $table->enum('metode_pembayaran', ['transfer_manual', 'payment_gateway'])
                      ->default('transfer_manual')
                      ->after('snap_token');
            }
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayarans', 'transaksi_id')) {
                $table->string('transaksi_id', 100)->nullable()->after('id_booking');
            }
            if (!Schema::hasColumn('pembayarans', 'metode')) {
                $table->string('metode', 50)->default('manual')->after('transaksi_id');
            }
        });

        // Make fields nullable in pembayarans so Payment Gateway can save without bank slip image
        try {
            DB::statement("ALTER TABLE pembayarans MODIFY no_rekening VARCHAR(30) NULL;");
            DB::statement("ALTER TABLE pembayarans MODIFY nama_rekening VARCHAR(100) NULL;");
            DB::statement("ALTER TABLE pembayarans MODIFY bank_pengirim VARCHAR(50) NULL;");
            DB::statement("ALTER TABLE pembayarans MODIFY bukti VARCHAR(255) NULL;");
        } catch (\Exception $e) {
            // Ignore if already nullable
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
            if (Schema::hasColumn('bookings', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            if (Schema::hasColumn('pembayarans', 'transaksi_id')) {
                $table->dropColumn('transaksi_id');
            }
            if (Schema::hasColumn('pembayarans', 'metode')) {
                $table->dropColumn('metode');
            }
        });
    }
}
