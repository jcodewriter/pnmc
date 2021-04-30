<?php

namespace App\Http\Controllers;

use App\Asset;
use App\MarketCapAggregate;
use App\VolumeAggregate;
use App\Helpers\Formatter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\DataTables;

class IndexController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /** @var Collection|Asset[] $assets */
        $assets = Cache::remember('assets:index', 10, function ()
        {
            return Asset::with([
                'history' => function ($query)
                {
                    /** @var \Illuminate\Database\Query\Builder $query */
                    $query->where('dateline', '>=', time() - 86400);
                }
            ])->get()->keyBy('ticker_symbol')
                ;
        });

        $fmt = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);

        return view('index', [
            'assets' => $assets,
            'totalConversions' => $fmt->formatCurrency(Cache::get('totalConversions', 0.00), 'USD'),
            'totalMarketCap' => $fmt->formatCurrency($assets->sum(function($model)
            {
                return $model['price'] * $model['supply'];
            }), 'USD'),
            'dailyVolume' => $fmt->formatCurrency($assets->sum('volume_price'), 'USD')
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function marketCap()
    {
        /** @var Collection|MarketCapAggregate[] $aggregates */
        $aggregates = MarketCapAggregate::orderByDesc('dateline')
            ->paginate(15)
        ;

        /** @var Collection|MarketCapAggregate[] $assets */
        $graphData = Cache::remember('market-cap:index', 60, function ()
        {
            /** @var Collection|MarketCapAggregate[] $aggregates */
            return MarketCapAggregate::orderByDesc('dateline')
                ->limit(30)
                ->get()
                ->reverse()
                ;
        });

        abort_if(empty($graphData), 404, trans('pegnet.market_cap_history_not_found'));

        return view('market_cap', [
            'aggregates' => $aggregates,
            'graphData' => $graphData->keyBy('dateline')
                ->map(function ($item, $key) {
                    return [
                        'legends' => [trans('pegnet.market_cap')],
                        'values' => [$item->market_cap]
                    ];
                })->all()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function getMarketCapData()
    {
        $model = MarketCapAggregate::query();

        return DataTables::of($model)
            ->editColumn('dateline', function ($entry) {
                /** @var MarketCapAggregate $entry */
                return Formatter::date($entry->dateline);
            })
            ->editColumn('market_cap', function ($entry) {
                /** @var MarketCapAggregate $entry */
                return Formatter::money($entry->market_cap);
            })
            ->make(false)
            ;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dailyVolume()
    {
        /** @var Collection|VolumeAggregate[] $assets */
        $graphData = Cache::remember('daily-volume:index', 60, function () {
            /** @var Collection|VolumeAggregate[] $aggregates */
            return VolumeAggregate::orderByDesc('dateline')
                ->limit(30)
                ->get()
                ->reverse();
        });

        abort_if(empty($graphData), 404, trans('pegnet.volume_history_not_found'));

        return view('volume', [
            'graphData' => $graphData->keyBy('dateline')
                ->map(function ($item, $key) {
                    return [
                        'colors' => ['#FFFFFF', '#28a745'],
                        'backgroundColors' => ['#1e3d60', '#28a745'],
                        'legends' => [trans('pegnet.pegnet_volume'), trans('pegnet.exchange_volume')],
                        'values' => [$item->volume, $item->exchange_volume]
                    ];
                })->all()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function getDailyVolumeData()
    {
        $model = VolumeAggregate::query();

        return DataTables::of($model)
            ->removeColumn('volume')
            ->removeColumn('exchange_volume')
            ->editColumn('dateline', function ($entry) {
                /** @var VolumeAggregate $entry */
                return Formatter::date($entry->dateline);
            })
            ->addColumn('total_volume', function ($entry) {
                /** @var VolumeAggregate $entry */
                return Formatter::number($entry->volume + $entry->exchange_volume);
            })
            ->addColumn('pegnet_volume', function ($entry) {
                /** @var VolumeAggregate $entry */
                return Formatter::number($entry->volume);
            })
            ->addColumn('parsed_exchange_volume', function ($entry) {
                /** @var VolumeAggregate $entry */
                return Formatter::number($entry->exchange_volume);
            })
            ->make(false)
            ;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getRichList()
    {
        $richest = [];
        try
        {
            $client = new Client();
            $response = $client->get(config('services.ticker.endpoint') . '/v1/rich');
            $richest = \GuzzleHttp\json_decode($response->getBody()
                ->getContents(), true);
        }
        catch (RequestException $e)
        {
            abort(500, sprintf('Error fetching rich list: %s', $e->getMessage()));
        }

        return view('rich_list', [
            'richest' => $richest
        ]);
    }
}
