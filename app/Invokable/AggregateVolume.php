<?php

namespace App\Invokable;

use App\AssetHistory;
use App\ExchangeHistory;
use App\VolumeAggregate;

class AggregateVolume
{
    public function __invoke(int $yesterday, int $today)
    {
        $volume = AssetHistory::selectRaw('SUM(price * volume) AS total_volume')
            ->whereBetween('dateline', [$yesterday, $today])
            ->value('total_volume')
        ;

        $exchangeVolume = ExchangeHistory::selectRaw('SUM(price * volume) AS total_volume')
            ->whereBetween('dateline', [$yesterday, $today])
            ->value('total_volume')
        ;

        VolumeAggregate::updateOrCreate([
            'dateline' => $today,
        ], [
            'volume' => sprintf("%.2f", $volume),
            'exchange_volume' => sprintf("%.2f", $exchangeVolume)
        ]);
    }
}
