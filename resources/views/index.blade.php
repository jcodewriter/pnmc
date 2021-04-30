@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('home'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-center">
                        <a href="{{ route('market-cap') }}">{{ __('pegnet.total_market_cap') }}: <span class="font-weight-bold">{{ $totalMarketCap }}</span></a>
                    </h2>
                    <h4 class="card-title text-center">
                        <a href="{{ route('daily-volume') }}">{{ __('pegnet.daily_volume') }}: <span class="font-weight-bold">{{ $dailyVolume }}</span></a>
                    </h4>
                    <h4 class="card-title text-center">
                        {{ __('pegnet.total_conversions') }}: <span class="">{{ $totalConversions }}</span>
                    </h4>
                </div>
{{--                <div class="card-body">--}}
{{--                    <div class="text-center count-up" data-timestamp="{{ config('app.start_date') }}" style="display: none">--}}
{{--                        {{ __('pegnet.mainnet_live_for') }}&nbsp;--}}
{{--                        <span class="value days">00</span>--}}
{{--                        <span class="timeRefDays">{{ __('generic.days') }}</span>,--}}
{{--                        <span class="value hours">00</span>--}}
{{--                        <span class="timeRefHours">{{ __('generic.hours') }}</span>,--}}
{{--                        <span class="value minutes">00</span>--}}
{{--                        <span class="timeRefMinutes">{{ __('generic.minutes') }}</span>--}}
{{--                        <span class="value seconds" style="display: none">00</span>--}}
{{--                        <span class="timeRefSeconds" style="display: none">{{ __('generic.seconds') }}</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-body">
            <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col">
                        @include('includes.asset_list')
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="stats">
                <i class="now-ui-icons ui-2_time-alarm"></i> @datetime($assets->first()->updated_at)
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/home.js') }}" defer></script>
@endpush
