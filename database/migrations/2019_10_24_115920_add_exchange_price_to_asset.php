<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExchangePriceToAsset extends Migration
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
            $table->unsignedDecimal('exchange_price', 20, 8)
                ->default(0.00000000)
                ->after('price')
            ;
            $table->unsignedDecimal('exchange_volume', 20, 8)
                ->default(0.00000000)
                ->after('volume')
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
            $table->dropColumn(['exchange_price', 'exchange_volume']);
        });
    }
}
