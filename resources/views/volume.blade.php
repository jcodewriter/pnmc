@extends('layouts.app')

{{--@section('breadcrumbs', Breadcrumbs::render('market_cap'))--}}

@section('meta_title'){{ __('meta.volume') }}@endsection
@section('meta_description'){{ __('meta.volume_descr') }}@endsection

@section('graph')
    <canvas class="lineGraph"
            data-chart-title="{{ __('pegnet.daily_volume_graph_title') }}"
            data-chart-series="USD"
            data-chart-point-interval="86400"
            data-chart-data='@json($graphData)'>
    </canvas>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('pegnet.volume_history') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-bordered--dataTable table-paged table-paged--sortable" style="width:100%;" data-ajax="{{ route('daily-volume-ajax') }}">
                            <thead class="text-primary">
                            <tr>
                                <th data-type="date">{{ __('generic.date') }}</th>
                                <th data-type="num-fmt">{{ __('pegnet.total_volume') }}</th>
                                <th data-type="num-fmt">{{ __('pegnet.pegnet_volume') }}</th>
                                <th data-type="num-fmt">{{ __('pegnet.exchange_volume') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/chartjs.js') }}" defer></script>
    <script src="{{ mix('js/chartjs.headerGraph.js') }}" defer></script>
    <script src="{{ mix('js/dataTables.js') }}" defer></script>
@endpush
