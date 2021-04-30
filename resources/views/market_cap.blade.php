@extends('layouts.app')

{{--@section('breadcrumbs', Breadcrumbs::render('market_cap'))--}}

@section('meta_title'){{ __('meta.market_cap') }}@endsection
@section('meta_description'){{ __('meta.market_cap_descr') }}@endsection

@section('graph')
    @include('includes.market_cap_graph')
@endsection

@section('content')
    @include('includes.market_cap_history')
@endsection

@push('scripts')
    <script src="{{ mix('js/chartjs.js') }}" defer></script>
    <script src="{{ mix('js/chartjs.headerGraph.js') }}" defer></script>
@endpush
