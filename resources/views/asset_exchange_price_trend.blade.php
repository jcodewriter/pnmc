@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('asset_exchange_price', $asset))

@section('meta_title'){{ __('meta.asset_exchange_price_trend', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection
@section('meta_description'){{ __('meta.asset_exchange_price_trend_descr', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection

@section('title')
    {{ $asset->title }}
@endsection

@section('subtitle')
    <img src="{{ Storage::disk('public')->url($asset->icon_file) }}" alt="{{ $asset->title }}" class="d-inline align-middle asset-icon asset-icon--medium header-image" height="64" width="64">
    {{ $asset->ticker_symbol }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-chart-large">
                <div class="card-header">
                    <h4 class="card-title">{{ __('pegnet.exchange_price_trend') }}</h4>
{{--                    <div class="dropdown">--}}
{{--                        <button type="button" class="btn btn-round dropdown-toggle btn-outline-default btn-icon no-caret" data-toggle="dropdown" aria-expanded="false">--}}
{{--                            <i class="now-ui-icons loader_gear"></i>--}}
{{--                        </button>--}}
{{--                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-135px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">--}}
{{--                            <a class="dropdown-item" href="#">Action</a>--}}
{{--                            <a class="dropdown-item" href="#">Another action</a>--}}
{{--                            <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                            <a class="dropdown-item text-danger" href="#">Remove Data</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                <div class="card-body">
                    <div class="chart-update-buttons">
                        <button class="btn btn-primary btn-simple btn-sm" data-time-period="day" data-chart-id="exchange_price_trend">1d</button>
                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="week" data-chart-id="exchange_price_trend">7d</button>
                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="month" data-chart-id="exchange_price_trend">1m</button>
{{--                            <button class="btn btn-secondary btn-simple btn-sm">3m</button>--}}
{{--                            <button class="btn btn-secondary btn-simple btn-sm" data-time-period="year" data-chart-id="exchange_price_trend">1y</button>--}}
{{--                            <button class="btn btn-secondary btn-simple btn-sm">YTD</button>--}}
                            <button class="btn btn-secondary btn-simple btn-sm" data-time-period="all" data-chart-id="exchange_price_trend">All</button>
                    </div>
                    @if($asset->ticker_symbol != 'PEG')
                    <div class="chart-overlay-buttons" style="display: none">
                        <button class="btn btn-secondary btn-simple btn-sm"
                                data-overlay-source="price_trend"
                                data-chart-color="#00d6b4"
                                data-chart-id="exchange_price_trend">{{ __('pegnet.pegnet_price') }}</button>
                    </div>
                    @endif
                    <div class="chart-area">
                        <canvas class="chartjs-render-monitor lineGraph" width="866" height="460" style="display: block; height: 190px; width: 433px;"
                                data-chart-series="USD"
                                data-chart-label="{{ __('pegnet.exchange_price') }}"
                                data-chart-id="exchange_price_trend"
                                data-chart-color="#f96332"
                                data-chart-data='@json($asset->exchangePriceHistory->pluck('price', 'dateline')->all())'>
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @money_format($asset->exchange_volume * $asset->exchange_price)<br />
                                        <small class="text-muted">@number_format($asset->exchange_volume) {{ $asset->ticker_symbol }}</small>
                                    </h3>

                                    <h6 class="stats-title">{{ __('pegnet.exchange_volume') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @money_format($asset->exchange_price)<br />
                                        &nbsp;
                                    </h3>
                                    <h6 class="stats-title">{{ __('pegnet.exchange_price') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @change_format($asset->exchange_price_change)<br />
                                        &nbsp;
                                    </h3>
                                    <h6 class="stats-title">{{ __('pegnet.exchange_price_change_full_24h') }}</h6>
                                </div>
                            </div>
                        </div>
                        @if($asset->ticker_symbol != 'PEG')
                        <div class="col-lg-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @number_format($priceDifference)%<br />
                                        &nbsp;
                                    </h3>
                                    <h6 class="stats-title">{{ __('pegnet.exchange_pegnet_price_difference_24h') }}</h6>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('pegnet.exchanges') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="{{ $dataTableId ?? 'coinTable' }}" class="table table-striped table-bordered table-bordered--dataTable" style="width:100%;" data-order='[[ 3, "desc" ]]'>
                            <thead class="text-primary">
                            <tr>
                                <th>{{ __('pegnet.exchange') }}</th>
                                <th>{{ __('pegnet.asset') }}</th>
                                <th data-type="num-fmt">{{ __('pegnet.price') }}</th>
                                <th>{{ __('pegnet.volume_24h') }}</th>
                                <th>{{ __('pegnet.price_change_24h') }}</th>
                                <th>{{ __('pegnet.spread') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($asset->exchanges as $entry)
                                <tr>
                                    <td>{{ __('pegnet.exchange_' . $entry->exchange) }}</td>
                                    <td>
                                        {!! app()->make('ExchangeData')->getLink($entry->exchange, $asset->ticker_symbol, $entry->quote_symbol) !!}
                                    </td>
                                    <td>@money_format($entry->price)</td>
                                    <td data-sort="{{ $entry->volume }}">
                                        @money_format($entry->volume * $entry->price)
                                        <div class="text-muted">@number_format($entry->volume) {{ $entry->ticker_symbol }}</div>
                                    </td>
                                    <td>
                                        @if($entry->price > 0.00)
                                            @change_format($entry->price_change)
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td data-sort="{{ $entry->spread }}">
                                        @if($entry->spread > 0.00)
                                            @number_format($entry->spread)%
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/chartjs.js') }}" defer></script>
    <script src="{{ mix('js/chartjs.lineGraph.js') }}" defer></script>
    <script src="{{ mix('js/dataTables.js') }}" defer></script>
@endpush
