<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVolumePriceColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table)
        {
            $table->unsignedDecimal('volume_price', 20, 8)
                ->default(0.00000000)
                ->after('volume')
            ;
            $table->unsignedDecimal('volume_in_price', 20, 8)
                ->default(0.00000000)
                ->after('volume_in')
            ;
            $table->unsignedDecimal('volume_out_price', 20, 8)
                ->default(0.00000000)
                ->after('volume_out')
            ;
            $table->unsignedDecimal('volume_tx_price', 20, 8)
                ->default(0.00000000)
                ->after('volume_tx')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->dropColumn(['volume_price', 'volume_in_price', 'volume_out_price', 'volume_tx_price']);
        });
    }
}
