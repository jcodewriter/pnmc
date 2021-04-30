@extends('layouts.admin')

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
