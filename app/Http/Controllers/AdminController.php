<?php

namespace App\Http\Controllers;

use App\MarketCapAggregate;
use Illuminate\Database\Eloquent\Collection;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /** @var Collection|MarketCapAggregate[] $aggregates */
        $aggregates = MarketCapAggregate::orderByDesc('dateline')
            ->paginate(15)
        ;

        /** @var Collection|MarketCapAggregate[] $aggregates */
        $graphData = MarketCapAggregate::orderByDesc('dateline')
            ->limit(30)
            ->get()
            ->reverse()
        ;

        abort_if(empty($graphData), 404, trans('pegnet.market_cap_history_not_found'));

        return view('admin.index', [
            'active' => 'home',
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
}
