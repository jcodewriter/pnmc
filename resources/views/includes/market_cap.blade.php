<div class="my-4 w-100 lineGraph"
     data-chart-title="{{ __('pegnet.market_cap_graph_title') }}"
     data-chart-series="USD"
     data-chart-point-start="{{ $graphData->first()->dateline }}"
     data-chart-point-interval="86400"
     data-chart-data='@json($graphData->pluck('market_cap')->all())'>
</div>

<h2>{{ __('pegnet.market_cap_history') }}</h2>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th>{{ __('generic.date') }}</th>
            <th>{{ __('pegnet.market_cap') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($aggregates as $aggregate)
            <tr>
                <td>@date($aggregate->dateline)</td>
                <td>@money_format($aggregate->market_cap)</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $aggregates->links() }}
</div>
