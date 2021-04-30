<?php

namespace App\Invokable;

use App\AssetHistory;
use App\MarketCapAggregate;

class AggregateMarketCap
{
    public function __invoke(int $date)
    {
        $marketCap = AssetHistory::selectRaw('SUM(price * supply) AS market_cap')
            ->where('dateline', function ($query) use ($date)
            {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->selectRaw('MAX(dateline)')
                    ->from('asset_history')
                    ->where('dateline', '<=', $date)
                ;
            })
            ->value('market_cap')
        ;
    
        MarketCapAggregate::updateOrCreate([
            'dateline' => $date,
        ], [
            'market_cap' => sprintf("%.2f", $marketCap)
        ]);
    }
}
