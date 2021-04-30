@extends('layouts.app')

{{--@section('breadcrumbs', Breadcrumbs::render('market_cap'))--}}

@section('meta_title'){{ __('meta.rich_list') }}@endsection
@section('meta_description'){{ __('meta.rich_list_descr') }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('pegnet.richest_addresses') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead class="text-primary">
                            <tr>
                                <th scope="col" data-type="num">#</th>
                                <th scope="col" data-type="num" class="text-center">{{ __('pegnet.address') }}</th>
                                <th scope="col" data-type="html-num-fmt">{{ __('pegnet.amount') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($richest as $i => $entry)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-center"><a href="https://pexplorer.factom.com/addresses/{{ $entry['address'] }}" target="_blank">{{ $entry['address'] }}</a></td>
                                    <td>
                                        @money_format($entry['pusd'])
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
