<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssetHistory;
use App\ExchangePriceHistory;
use App\MarketCapAggregate;
use App\Helpers\Formatter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class AssetController extends Controller
{
    /**
     * @param string $tickerSymbol
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(string $tickerSymbol)
    {
        if ($tickerSymbol == 'all')
        {
            abort(404);
            /** @var Collection|AssetHistory[] $history */
            $history = AssetHistory::orderByDesc('height')
                ->paginate(15)
            ;

            return view('asset', [
                'history' => $history
            ]);
        }
        else
        {
            /** @var Asset $asset */
            $asset = Cache::remember('asset:' . $tickerSymbol, 10, function () use ($tickerSymbol)
            {
                return Asset::with([
                    'history' => function ($query)
                    {
                        /** @var \Illuminate\Database\Query\Builder $query */
                        $query->where('dateline', '>=', time() - 86400);
                    },
                    'exchangePriceHistory' => function ($query)
                    {
                        /** @var \Illuminate\Database\Query\Builder $query */
                        $query->where('dateline', '>=', time() - 86400);
                    }
                ])->find($tickerSymbol);
            });

            return view('asset', [
                'asset' => $asset
            ]);
        }
    }

    /**
     * @param string $tickerSymbol
     * @return mixed
     * @throws \Exception
     */
    public function getAssetHistoryData(string $tickerSymbol)
    {
        $history = AssetHistory::select(['dateline', 'height', 'price', 'volume', 'volume_in', 'volume_out', 'volume_tx', 'supply', 'ticker_symbol'])
            ->where('ticker_symbol', $tickerSymbol)
        ;

        return DataTables::of($history)
            ->editColumn('dateline', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::dateTime($entry->dateline);
            })
            ->editColumn('price', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::money($entry->price);
            })
            ->editColumn('volume', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::money($entry->volume * $entry->price) . "
                    <div class=\"text-muted\">" . Formatter::number($entry->volume) . " {$entry->ticker_symbol}</div>
                ";
            })
            ->editColumn('volume_in', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::money($entry->volume_in * $entry->price) . "
                    <div class=\"text-muted\">" . Formatter::number($entry->volume_in) . " {$entry->ticker_symbol}</div>
                ";
            })
            ->editColumn('volume_out', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::money($entry->volume_out * $entry->price) . "
                    <div class=\"text-muted\">" . Formatter::number($entry->volume_out) . " {$entry->ticker_symbol}</div>
                ";
            })
            ->editColumn('volume_tx', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::money($entry->volume_tx * $entry->price) . "
                    <div class=\"text-muted\">" . Formatter::number($entry->volume_tx) . " {$entry->ticker_symbol}</div>
                ";
            })
            ->editColumn('supply', function ($entry) {
                /** @var AssetHistory $entry */
                return Formatter::number($entry->supply);
            })
            ->removeColumn('ticker_symbol')
            ->rawColumns([3, 4, 5, 6])
            ->make(false)
            ;
    }

    /**
     * @param string $tickerSymbol
     * @return mixed
     * @throws \Exception
     */
    public function getAssetRichList(string $tickerSymbol)
    {
        /** @var Asset $asset */
        $asset = Cache::remember('asset:' . $tickerSymbol, 10, function () use ($tickerSymbol)
        {
            return Asset::with([
                'history' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                },
                'exchangePriceHistory' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                }
            ])->find($tickerSymbol);
        });

        $richest = [];
        try
        {
            $client = new Client();
            $response = $client->get(config('services.ticker.endpoint') . '/v1/rich/' . $tickerSymbol);
            $richest = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);
        }
        catch (RequestException $e)
        {
            abort(500, sprintf('Error fetching rich list: %s', $e->getMessage()));
        }

        return view('asset_rich_list', [
            'asset' => $asset,
            'richest' => $richest
        ]);
    }

    /**
     * @param string $tickerSymbol
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFullPriceGraph(string $tickerSymbol)
    {
        /** @var Asset $asset */
        $asset = Cache::remember('asset:' . $tickerSymbol, 10, function () use ($tickerSymbol)
        {
            return Asset::with([
                'history' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                },
                'exchangePriceHistory' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                }
            ])->findOrFail($tickerSymbol);
        });

        $pegnetPrice = $asset->history->avg('price');
        $exchangePrice = $asset->exchangePriceHistory->avg('price');

        return view('asset_price_trend', [
            'asset' => $asset,
            'priceDifference' => sprintf("%.2f", ($pegnetPrice || $exchangePrice) ? ((abs($pegnetPrice - $exchangePrice) / (($pegnetPrice + $exchangePrice) / 2)) * 100) : 0),
        ]);
    }

    /**
     * @param string $tickerSymbol
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFullExchangePriceGraph(string $tickerSymbol)
    {
        /** @var Asset $asset */
        $asset = Cache::remember('asset:' . $tickerSymbol, 10, function () use ($tickerSymbol)
        {
            return Asset::with([
                'history' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                },
                'exchangePriceHistory' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                }
            ])->findOrFail($tickerSymbol);
        });

        $pegnetPrice = $asset->history->avg('price');
        $exchangePrice = $asset->exchangePriceHistory->avg('price');

        return view('asset_exchange_price_trend', [
            'asset' => $asset,
            'priceDifference' => sprintf("%.2f", ($pegnetPrice || $exchangePrice) ? ((abs($pegnetPrice - $exchangePrice) / (($pegnetPrice + $exchangePrice) / 2)) * 100) : 0),
        ]);
    }

    /**
     * @param string $tickerSymbol
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFullVolumeGraph(string $tickerSymbol)
    {
        /** @var Asset $asset */
        $asset = Cache::remember('asset:' . $tickerSymbol, 10, function () use ($tickerSymbol)
        {
            return Asset::with([
                'history' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                },
                'exchangePriceHistory' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                }
            ])->findOrFail($tickerSymbol);
        });

        return view('asset_volume', [
            'asset' => $asset,
        ]);
    }

    /**
     * @param string $tickerSymbol
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFullSupplyGraph(string $tickerSymbol)
    {
        /** @var Asset $asset */
        $asset = Cache::remember('asset:' . $tickerSymbol, 10, function () use ($tickerSymbol)
        {
            return Asset::with([
                'history' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                },
                'exchangePriceHistory' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                }
            ])->findOrFail($tickerSymbol);
        });

        return view('asset_supply', [
            'asset' => $asset,
        ]);
    }

    /**
     * @param Request $request
     * @param string $tickerSymbol
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpdatedGraphData(Request $request, string $tickerSymbol)
    {
        $input = $request->all();

        if (empty($input['period']) || empty($input['chart'])) {
            return response()->json(['error' => true]);
        }

        switch ($input['chart']) {
            case 'exchange_price_trend':
                $priceOnly = true;
                $query = ExchangePriceHistory::where('ticker_symbol', $tickerSymbol);
                break;

            default:
                $priceOnly = false;
                $query = AssetHistory::where('ticker_symbol', $tickerSymbol);
                break;
        }

        switch ($input['period']) {
            case 'day':
                $query->selectRaw('dateline, price AS price_trend' . (!$priceOnly ? ', (volume * price) AS volume_trend, supply AS supply_trend' : ''))
                    ->where('dateline', '>=', time() - 86400);
                break;

            case 'week':
                $query->selectRaw('dateline, AVG(price) AS price_trend' . (!$priceOnly ? ', AVG(volume * price) AS volume_trend, AVG(supply) AS supply_trend' : ''))
                    ->where('dateline', '>=', strtotime('midnight 7 days ago'))
                    ->groupBy(\DB::raw('DAYOFYEAR(updated_at), FLOOR(HOUR(updated_at) / 2)'));
                break;

            case 'month':
                $query->selectRaw('dateline, AVG(price) AS price_trend' . (!$priceOnly ? ', AVG(volume * price) AS volume_trend, AVG(supply) AS supply_trend' : ''))
                    ->where('dateline', '>=', strtotime('midnight 30 days ago'))
                    ->groupBy(\DB::raw('DAYOFYEAR(updated_at), FLOOR(HOUR(updated_at) / 6)'));
                break;

            case 'year':
                $query->selectRaw('dateline, AVG(price) AS price_trend' . (!$priceOnly ? ', AVG(volume * price) AS volume_trend, AVG(supply) AS supply_trend' : ''))
                    ->where('dateline', '>=', strtotime('midnight 1 year ago'))
                    ->groupBy(\DB::raw('FLOOR(DAY(updated_at) / 2)'));
                break;

            case 'all':
                $query->selectRaw('dateline, AVG(price) AS price_trend' . (!$priceOnly ? ', AVG(volume * price) AS volume_trend, AVG(supply) AS supply_trend' : ''))
                    ->groupBy(\DB::raw('DATE(updated_at)'));
//                    ->groupBy(\DB::raw('YEARWEEK(updated_at)'));
                break;
        }

//        dd(Str::replaceArray('?', $query->getBindings(), $query->toSql()));
        // @TODO: Write some form of cache registry that lets me cache this based on input
        $result = $query->get();

        $data = [];
        switch ($input['chart']) {
            case 'volume_trend':
                $data = $result->pluck('volume_trend', 'dateline')
                    ->all();
                break;

            case 'supply_trend':
                $data = $result->pluck('supply_trend', 'dateline')
                    ->all();
                break;

            case 'exchange_price_trend':
            case 'price_trend':
                $data = $result->pluck('price_trend', 'dateline')
                    ->all();
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpdatedMarketCapGraphData(Request $request)
    {
        $input = $request->all();

        if (empty($input['period']) || empty($input['chart'])) {
            return response()->json(['error' => true]);
        }

        $query = MarketCapAggregate::getQuery();

        switch ($input['period']) {
            case 'week':
                $query->orderByDesc('dateline')
                    ->limit(7);
                break;

            case 'month':
                $query->orderByDesc('dateline')
                    ->limit(30);
                break;

            case 'year':
                $query->selectRaw('dateline, AVG(market_cap) AS market_cap')
                    ->where('dateline', '>=', strtotime('midnight 1 year ago'))
                    ->groupBy(\DB::raw('FLOOR(DAY(FROM_UNIXTIME(dateline)) / 2)'));
                break;

            case 'all':
                $query->orderByDesc('dateline');
                /*
                $query->selectRaw('dateline, AVG(market_cap) AS market_cap')
                    ->groupBy(\DB::raw('YEARWEEK(FROM_UNIXTIME(dateline))'));
                */
                break;
        }

//        dd(Str::replaceArray('?', $query->getBindings(), $query->toSql()));
        // @TODO: Write some form of cache registry that lets me cache this based on input
        $result = $query->get();

        return response()->json([
            'success' => true,
            'data' => $result->pluck('market_cap', 'dateline')
                ->all()
        ]);
    }
}
