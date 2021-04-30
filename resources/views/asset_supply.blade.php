@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('asset_supply', $asset))

@section('meta_title'){{ __('meta.asset_supply', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection
@section('meta_description'){{ __('meta.asset_supply_descr', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection

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
                    <h4 class="card-title">{{ __('pegnet.supply_trend') }}</h4>
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
                        <button class="btn btn-primary btn-simple btn-sm" data-time-period="day" data-chart-id="supply_trend">1d</button>
                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="week" data-chart-id="supply_trend">7d</button>
                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="month" data-chart-id="supply_trend">1m</button>
{{--                        <button class="btn btn-secondary btn-simple btn-sm">3m</button>--}}
{{--                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="year" data-chart-id="supply_trend">1y</button>--}}
{{--                        <button class="btn btn-secondary btn-simple btn-sm">YTD</button>--}}
{{--                        <button class="btn btn-secondary btn-simple btn-sm" data-time-period="all" data-chart-id="supply_trend">All</button>--}}
                    </div>
                    <div class="chart-area">
                        <canvas class="chartjs-render-monitor lineGraph" width="866" height="460" style="display: block; height: 190px; width: 433px;"
                                data-chart-series="{{ __('pegnet.supply') }}"
                                data-chart-label="{{ __('pegnet.supply') }}"
                                data-chart-id="supply_trend"
                                data-chart-data='@json($asset->history->pluck('supply', 'dateline')->all())'>
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
                        <div class="col-lg-6">
                            <div class="statistics">
                                <div class="info">
                                    <h3 class="info-title">@number_format($asset->supply) {{ $asset->ticker_symbol }}</h3>
                                    <h6 class="stats-title">{{ __('pegnet.supply') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
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
@endsection

@push('scripts')
    <script src="{{ mix('js/chartjs.js') }}" defer></script>
    <script src="{{ mix('js/chartjs.lineGraph.js') }}" defer></script>
@endpush
