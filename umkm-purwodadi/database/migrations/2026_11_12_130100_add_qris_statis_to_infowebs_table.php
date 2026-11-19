<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrisStatisToInfowebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infowebs', function (Blueprint $table) {
            if (!Schema::hasColumn('infowebs', 'qris_statis')) {
                $table->text('qris_statis')->nullable()->after('atas_nama_toko');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infowebs', function (Blueprint $table) {
            if (Schema::hasColumn('infowebs', 'qris_statis')) {
                $table->dropColumn('qris_statis');
            }
        });
    }
}

