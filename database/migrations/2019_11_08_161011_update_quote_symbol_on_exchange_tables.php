<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuoteSymbolOnExchangeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update("UPDATE exchange SET quote_symbol = 'BTC'");
        DB::update("UPDATE exchange_history SET quote_symbol = 'BTC'");
        DB::update("UPDATE exchange_price_history SET quote_symbol = 'BTC'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
