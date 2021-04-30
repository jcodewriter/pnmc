<?php

namespace App\Traits;

trait IsTradingPairIncluded
{
    /**
     * @param $tickerSymbol
     * @param $quoteSymbol
     * @return bool
     */
    public function isIncluded($tickerSymbol, $quoteSymbol): bool
    {
        $included = true;
        foreach ($this->tradingPairs as $tradingPair)
        {
            if ($tradingPair[0] === $tickerSymbol && $tradingPair[1] === $quoteSymbol)
            {
                $included = $tradingPair[2];
                break;
            }
        }

        return $included;
    }
}
