<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertExchangePriceHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ([
            ['PEG' => 'BTC'],
            ['PEG' => 'ETH'],
            ['PEG' => 'ETH'],
            ['pFCT' => 'BTC'],
            ['pFCT' => 'ETH'],

            ['pUSD' => 'BTC'],
            ['pUSD' => 'ETH'],
            ['pUSD' => 'USDT'],
            ['pUSD' => 'VITE'],
        ] as $tradingPairs) {
            foreach ($tradingPairs as $tickerSymbol => $quoteSymbol) {
                $dateStamp = DB::selectOne('
                        SELECT MIN(dateline) AS dateline
                        FROM exchange_price_history
                        WHERE ticker_symbol = ?
                            AND quote_symbol = ?
                 ', [$tickerSymbol, $quoteSymbol])->dateline;

                while ($dateStamp > 1571101560) {
                    DB::insert("
                        INSERT IGNORE INTO exchange_price_history
                            (ticker_symbol, quote_symbol, dateline, price, updated_at)
                        VALUES 
                            ('$tickerSymbol', '$quoteSymbol', $dateStamp, 0, FROM_UNIXTIME($dateStamp))
                    ");

                    $dateStamp -= 600;
                }
            }
        }
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
