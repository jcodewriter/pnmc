@extends('layouts.app')

@section('breadcrumbs', Breadcrumbs::render('asset_rich_list', $asset))

@section('meta_title'){{ __('meta.asset_rich_list', ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection
@section('meta_description'){{ __(($asset->ticker_symbol == 'PEG' ? 'meta.asset_rich_list_short_descr' : 'meta.asset_rich_list_descr'), ['asset' => ($asset->ticker_symbol == 'PEG' ? 'PegNet Token' : $asset->title), 'ticker' => $asset->ticker_symbol]) }}@endsection

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
                                <th scope="col">{{ $asset->ticker_symbol }}</th>
                                <th scope="col" data-type="num-fmt">{{ __('pegnet.amount') }}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($richest as $i => $entry)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-center"><a href="https://pexplorer.factom.com/addresses/{{ $entry['address'] }}" target="_blank">{{ $entry['address'] }}</a></td>
                                    <td>@number_format($entry['amount']) </td>
                                    <td>@money_format($entry['pusd'])</td>
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
