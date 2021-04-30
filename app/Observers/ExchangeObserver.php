<?php

namespace App\Observers;

use App\Exchange;
use App\ExchangeHistory;

class ExchangeObserver
{
    /**
     * Handle the exchange "creating" event.
     *
     * @param  Exchange  $exchange
     * @return void
     */
    public function creating(Exchange $exchange)
    {
        $exchange->price_change = 100.00;
    }

    /**
     * Handle the exchange "updating" event.
     *
     * @param  Exchange  $exchange
     * @return void
     */
    public function updating(Exchange $exchange)
    {
        /** @var ExchangeHistory|null $result */
        $result = ExchangeHistory::where('exchange', $exchange->exchange)
            ->where('ticker_symbol', $exchange->ticker_symbol)
            ->where('quote_symbol', $exchange->quote_symbol)
            ->where('dateline', '<=', strtotime('midnight today') - 1)
            ->orderBy('dateline', 'DESC')
            ->limit(1)
            ->get()
            ->first()
        ;
        if ($result)
        {
            if ($exchange->price == 0.00 && $result->price == 0.00)
            {
                $exchange->price_change = 0.0;
            }
            else if ($result->price > 0.0)
            {
                $exchange->price_change = sprintf("%.2f", (($exchange->price - $result->price) / $result->price) * 100);
            }
            else
            {
                $exchange->price_change = 0.0;
            }
        }
        else
        {
            $exchange->price_change = 0.0;
        }
    }
}
