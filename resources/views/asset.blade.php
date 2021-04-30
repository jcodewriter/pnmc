@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('asset', $asset ?? 'all'))

@if(isset($asset) && is_object($asset))
    @section('meta_title'){{ __('meta.asset_overview', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection
    @section('meta_description'){{ __(($asset->ticker_symbol == 'PEG' ? 'meta.asset_overview_short_descr' : 'meta.asset_overview_descr'), ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection

    @section('title')
        {{ $asset->title }}
    @endsection

    @section('subtitle')
        <img src="{{ Storage::disk('public')->url($asset->icon_file) }}" alt="{{ $asset->title }}" class="d-inline align-middle asset-icon asset-icon--medium header-image" height="64" width="64">
        {{ $asset->ticker_symbol }}
    @endsection
@else
    @section('meta_title'){{ __('meta.asset_overview_all') }}@endsection
    @section('meta_description'){{ __('meta.asset_overview_all_descr') }}@endsection
@endif

@section('content')
    @if(isset($asset) && is_object($asset))
        <div class="row">
            <div class="col-md-12">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">@money_format($asset->price * $asset->supply)</h3>
                                        <h6 class="stats-title">{{ __('pegnet.market_cap') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">
                                            @if($asset->exchange_price > 0.00)
                                                @money_format($asset->exchange_price)
                                            @else
                                                ---
                                            @endif
                                        </h3>
                                        <h6 class="stats-title">{{ __('pegnet.exchange_price') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @if($asset->ticker_symbol != 'PEG')
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">
                                            @if($asset->ticker_symbol != 'PEG')
                                                @money_format($asset->price)
                                            @else
                                                ---
                                                <a tabindex="0" class="btn btn-link" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ __('pegnet.peg_popover_explain') }}">
                                                    <i class="now-ui-icons travel_info"></i>
                                                </a>
                                            @endif
                                        </h3>
                                        <h6 class="stats-title">{{ __('pegnet.pegnet_price') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">@number_format($asset->supply) {{ $asset->ticker_symbol }}</h3>
                                        <h6 class="stats-title">{{ __('pegnet.supply') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">
                                            @money_format($asset->volume_price)
                                            <small class="text-muted">@number_format($asset->volume) {{ $asset->ticker_symbol }}</small>
                                        </h3>
                                        <h6 class="stats-title">{{ __('pegnet.volume_24h') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">
                                            @if($asset->exchange_price > 0.00)
                                                @change_format($asset->exchange_price_change)
                                            @else
                                                ---
                                            @endif
                                        </h3>
                                        <h6 class="stats-title">{{ __('pegnet.exchange_price_change_full_24h') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @if($asset->ticker_symbol != 'PEG')
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">
                                            @if($asset->ticker_symbol != 'PEG')
                                                @change_format($asset->price_change)
                                            @else
                                                ---
                                                <button type="button" class="btn btn-link" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ __('pegnet.peg_popover_explain') }}">
                                                    <i class="now-ui-icons travel_info"></i>
                                                </button>
                                            @endif
                                        </h3>
                                        <h6 class="stats-title">{{ __('pegnet.pegnet_price_change_full_24h') }}</h6>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-{{ $asset->ticker_symbol != 'PEG' ? '3' : '4' }}">
                                <div class="statistics">
                                    <div class="info">
                                        <h3 class="info-title">@change_format($asset->supply_change)</h3>
                                        <h6 class="stats-title">{{ __('pegnet.supply_change_24h') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="now-ui-icons ui-2_time-alarm"></i> @datetime($asset->updated_at)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @if($asset->ticker_symbol != 'PEG')
            <div class="{{ $asset->exchange_price > 0.00 ? 'col-lg-6' : 'col-lg-12' }}">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4 class="card-title"><a href="{{ route('asset-price-trend', $asset) }}">{{ __('pegnet.pegnet_price_trend_24h') }}</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                            <canvas class="chartjs-render-monitor lineGraph" width="866" height="380" style="display: block; height: 190px; width: 433px;"
                                    data-chart-title="{{ __('pegnet.price_graph_title') }}"
                                    data-chart-series="USD"
                                    data-chart-point-interval="600"
                                    data-chart-data='@json($asset->history->pluck('price', 'dateline')->all())'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($asset->exchange_price > 0.00)
            <div class="col-lg-{{ $asset->ticker_symbol != 'PEG' ? '6' : '12' }}">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4 class="card-title"><a href="{{ route('asset-exchange-price-trend', $asset) }}">{{ __('pegnet.exchange_price_trend_24h') }}</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                            <canvas class="chartjs-render-monitor lineGraph" width="866" height="380" style="display: block; height: 190px; width: 433px;"
                                    data-chart-title="{{ __('pegnet.exchange_price_graph_title') }}"
                                    data-chart-series="USD"
                                    data-chart-point-interval="600"
                                    data-chart-data='@json($asset->exchangePriceHistory->pluck('price', 'dateline')->all())'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4 class="card-title"><a href="{{ route('asset-volume', $asset) }}">{{ __('pegnet.volume_24h') }}</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                            <canvas class="chartjs-render-monitor lineGraph" width="866" height="380" style="display: block; height: 190px; width: 433px;"
                                    data-chart-title="{{ __('pegnet.volume_graph_title') }}"
                                    data-chart-series="{{ __('pegnet.total_volume') }}"
                                    data-chart-point-interval="600"
    {{--                                data-chart-series="USD"--}}
                                    data-chart-data='@json($asset->history->pluck('volume', 'dateline')->all())'>
    {{--                                data-chart-data='@json($asset->history->map(function($item, $key) { return $item->volume * $item->price; })->all())'>--}}
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-chart">
                    <div class="card-header">
                        <h4 class="card-title"><a href="{{ route('asset-supply', $asset) }}">{{ __('pegnet.supply_change_24h') }}</a></h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-area"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                            <canvas class="chartjs-render-monitor lineGraph" width="866" height="380" style="display: block; height: 190px; width: 433px;"
                                    data-chart-title="{{ __('pegnet.supply_graph_title') }}"
                                    data-chart-series="{{ __('pegnet.supply') }}"
                                    data-chart-point-interval="600"
                                    data-chart-data='@json($asset->history->pluck('supply', 'dateline')->all())'>
                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('pegnet.asset_history') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-bordered--dataTable table-paged table-paged--sortable" style="width:100%;"
                                   data-ajax="{{ route('asset-history-ajax', $asset) }}"
{{--                                   data-columns="[{data: 'id', name: 'id'}, {data: 'name', name: 'name'}, {data: 'email', name: 'email'}, {data: 'created_at', name: 'created_at'}, {data: 'updated_at', name: 'updated_at'}, {data: 'action', name: 'action'}]"--}}
                            >
                                <thead class="text-primary">
                                <tr>
                                    <th data-type="date">{{ __('generic.date') }}</th>
                                    <th data-type="num">{{ __('pegnet.block') }}</th>
                                    <th data-type="html-num-fmt">{{ __('pegnet.price') }}</th>
                                    <th data-type="html-num-fmt">{{ __('pegnet.total_volume') }}</th>
                                    <th data-type="html-num-fmt">{{ __('pegnet.volume_in') }}</th>
                                    <th data-type="html-num-fmt">{{ __('pegnet.volume_out') }}</th>
                                    <th data-type="html-num-fmt">{{ __('pegnet.volume_tx') }}</th>
                                    <th data-type="num-fmt">{{ __('pegnet.supply') }}</th>
                                </tr>
                                </thead>
{{--                                <tbody>--}}
{{--                                --}}
{{--                                @foreach ($history as $entry)--}}
{{--                                    <tr>--}}
{{--                                        <td>@datetime($entry->dateline)</td>--}}
{{--                                        <td>{{ $entry->height }}</td>--}}
{{--                                        <td>@money_format($entry->price)</td>--}}
{{--                                        <td>--}}
{{--                                            @money_format($entry->volume * $entry->price)--}}
{{--                                            <div class="text-muted">@number_format($entry->volume) {{ $entry->ticker_symbol }}</div>--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            @money_format($entry->volume_in * $entry->price)--}}
{{--                                            <div class="text-muted">@number_format($entry->volume_in) {{ $entry->ticker_symbol }}</div>--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            @money_format($entry->volume_out * $entry->price)--}}
{{--                                            <div class="text-muted">@number_format($entry->volume_out) {{ $entry->ticker_symbol }}</div>--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            @money_format($entry->volume_tx * $entry->price)--}}
{{--                                            <div class="text-muted">@number_format($entry->volume_tx) {{ $entry->ticker_symbol }}</div>--}}
{{--                                        </td>--}}
{{--                                        <td>@number_format($entry->supply)</td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ mix('js/chartjs.js') }}" defer></script>
    <script src="{{ mix('js/chartjs.smallGraph.js') }}" defer></script>
    <script src="{{ mix('js/dataTables.js') }}" defer></script>
@endpush
