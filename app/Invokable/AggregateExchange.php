<?php

namespace App\Invokable;

use Illuminate\Support\Facades\DB;

/**
 * Class AggregateExchange
 * @package App\Invokable
 */
class AggregateExchange
{
    /**
     * @param int $date
     * @throws \Exception
     */
    public function __invoke(int $date)
    {
        DB::insert('
            REPLACE INTO exchange_history
            SELECT ticker_symbol, quote_symbol, exchange, ?, price, volume, ?
            FROM exchange
        ', [
            $date,
            (new \DateTime())->setTimestamp($date)->format('Y-m-d H:i:s')
        ]);
    }
}
