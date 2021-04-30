@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('asset_volume', $asset))

@section('meta_title'){{ __('meta.asset_volume', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection
@section('meta_description'){{ __('meta.asset_volume_descr', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection

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
                    <h4 class="card-title">{{ __('pegnet.pegnet_volume') }}</h4>
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
                        <button class="btn btn-primary btn-simple btn-sm" data-time-period="day" data-chart-id="volume_trend">1d</button>
                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="week" data-chart-id="volume_trend">7d</button>
                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="month" data-chart-id="volume_trend">1m</button>
{{--                        <button class="btn btn-secondary btn-simple btn-sm">3m</button>--}}
{{--                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="year" data-chart-id="volume_trend">1y</button>--}}
{{--                        <button class="btn btn-secondary btn-simple btn-sm">YTD</button>--}}
{{--                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="all" data-chart-id="volume_trend">All</button>--}}
                    </div>
                    <div class="chart-area">
                        <canvas class="chartjs-render-monitor lineGraph" width="866" height="460" style="display: block; height: 190px; width: 433px;"
{{--                                data-chart-title="{{ __('pegnet.volume_trend') }}"--}}
                                data-chart-series="USD"
                                data-chart-label="{{ __('pegnet.volume') }}"
                                data-chart-id="volume_trend"
                                data-chart-data='@json($asset->history->keyBy('dateline')->map(function($item, $key) { return $item->volume * $item->price; })->all())'>
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
                        <div class="col-lg-4">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @money_format($asset->volume_price + ($asset->exchange_price * $asset->exchange_volume))
                                        <small class="text-muted">@number_format($asset->volume + $asset->exchange_volume) {{ $asset->ticker_symbol }}</small>
                                    </h3>
                                    <h6 class="stats-title">{{ __('pegnet.total_volume_24h') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @money_format($asset->volume_price)
                                        <small class="text-muted">@number_format($asset->volume) {{ $asset->ticker_symbol }}</small>
                                    </h3>
                                    <h6 class="stats-title">{{ __('pegnet.pegnet_volume') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">
                                        @if($asset->exchange_price > 0.00)
                                            @money_format(($asset->exchange_price * $asset->exchange_volume))
                                            <small class="text-muted">@number_format($asset->exchange_volume) {{ $asset->ticker_symbol }}</small>
                                        @else
                                            ---
                                        @endif
                                    </h3>
                                    <h6 class="stats-title">{{ __('pegnet.exchange_volume') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/chartjs.js') }}" defer></script>
    <script src="{{ mix('js/chartjs.lineGraph.js') }}" defer></script>
@endpush
