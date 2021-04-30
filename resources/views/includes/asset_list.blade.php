<table id="{{ $dataTableId ?? 'coinTable' }}" class="table table-striped table-bordered table-bordered--dataTable" style="width:100%;">
    <thead>
    <tr>
        <th data-type="num">#</th>
        <th>{{ __('pegnet.passet') }}</th>
        <th data-type="num-fmt">{{ __('pegnet.market_cap') }}</th>
        <th data-type="num-fmt">{{ __('pegnet.pegnet_price') }}</th>
        <th data-type="num-fmt">{{ __('pegnet.exchange_price') }}</th>
        <th data-type="num-fmt">{{ __('pegnet.total_volume_24h') }}</th>
        <th data-type="num-fmt">{{ __('pegnet.supply') }}</th>
        <th data-orderable="false">{{ __('pegnet.pegnet_price_24h') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($assets as $asset)
        @include('includes.asset_bit')
    @endforeach
    </tbody>
</table>
