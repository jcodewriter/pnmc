<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteSymbolToExchangeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange', function (Blueprint $table) {
            $table->string('quote_symbol', 10)->after('ticker_symbol');
            $table->dropPrimary();
            $table->primary(['ticker_symbol', 'quote_symbol', 'exchange'], 'ticker_symbol_exchange');
        });
        Schema::table('exchange_history', function (Blueprint $table) {
            $table->string('quote_symbol', 10)->after('ticker_symbol');
            $table->dropPrimary();
            $table->primary(['ticker_symbol', 'quote_symbol', 'exchange', 'dateline'], 'ticker_symbol_exchange_date');
        });
        Schema::table('exchange_price_history', function (Blueprint $table) {
            $table->string('quote_symbol', 10)->after('ticker_symbol');
            $table->dropPrimary();
            $table->primary(['ticker_symbol', 'quote_symbol', 'dateline'], 'ticker_symbol_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange', function (Blueprint $table) {
            $table->dropColumn('quote_symbol');
        });
        Schema::table('exchange_history', function (Blueprint $table) {
            $table->dropColumn('quote_symbol');
        });
        Schema::table('exchange_price_history', function (Blueprint $table) {
            $table->dropColumn('quote_symbol');
        });
    }
}
