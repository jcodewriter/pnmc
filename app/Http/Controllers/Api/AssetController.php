<?php

namespace App\Http\Controllers\Api;

use App\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AssetController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getAssetData(Request $request)
    {
        /** @var Collection|Asset[] $assets */
        $assets = Cache::remember('assets:api', 10, function ()
        {
            return Asset::all();
        });

        /** @var Asset $biggestPriceGain */
        $biggestPriceGain = $assets->sortBy('price_change', SORT_REGULAR, true)->first();

        /** @var Asset $biggestSupplyGain */
        $biggestSupplyGain = $assets->sortBy('supply_change', SORT_REGULAR, true)->first();

        /** @var Asset $topVolume */
        $topVolume = $assets->sortBy(function ($item) {
            /** @var Asset $item */
            return $item->volume * $item->price;
        }, SORT_REGULAR, true)->first();

        return [
            'market_cap' => $assets->sum(function($model)
            {
                return $model['price'] * $model['supply'];
            }),
            'biggest_price_gain' => [
                'asset' => $biggestPriceGain->ticker_symbol,
                'gain' => $biggestPriceGain->price_change
            ],
            'biggest_supply_gain' => [
                'asset' => $biggestSupplyGain->ticker_symbol,
                'gain' => $biggestSupplyGain->supply_change
            ],
            'top_volume' => [
                'asset' => $topVolume->ticker_symbol,
                'volume' => $topVolume->volume,
                'price' => $topVolume->price
            ],
            'peg_token' => $assets->filter(function ($value, $key) {
                return mb_strtoupper($value->ticker_symbol) == 'PEG';
            })->first()
        ];
    }

    /**
     * @param Request $request
     * @param string $tickerSymbol
     * @return Asset|Asset[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getAsset(Request $request, string $tickerSymbol)
    {
        $columns = ['*'];
        $with = [];

        if ($request->get('include_history'))
        {
            $with['history'] = function ($query)
            {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->where('dateline', '>=', time() - 86400);
            };
        }

        if ($request->get('include_exchange_history'))
        {
            $with['exchangeHistory'] = function ($query)
            {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->where('dateline', '>=', time() - 86400);
            };
        }

        if ($request->get('include_exchange_price_history'))
        {
            $with['exchangePriceHistory'] = function ($query)
            {
                /** @var \Illuminate\Database\Query\Builder $query */
                $query->where('dateline', '>=', time() - 2629743);
            };
        }

        if ($request->has('columns'))
        {
            $columns = Asset::filterForValidColumns(explode(',', $request->get('columns')));
        }

        if ($tickerSymbol == 'all')
        {
            return Asset::with($with)->get($columns);
        }
        else
        {
            return Asset::with($with)->find($tickerSymbol, $columns);
        }
    }
}
