<tr>
    <td>{{ $loop->iteration }}</td>
    <td class="no-wrap" data-search="{{ $asset->title }},{{ $asset->ticker_symbol }}" data-sort="{{ $asset->title }}">
        <a href="{{ route('asset', $asset->ticker_symbol) }}">
            <img src="{{ Storage::disk('public')->url($asset->icon_file) }}" alt="{{ $asset->title }}" class="d-inline align-middle asset-icon asset-icon--medium" height="64" width="64">
        </a>
        <div class="d-inline-block pl-1 align-middle">
            <div class="no-wrap"><a href="{{ route('asset', $asset->ticker_symbol) }}" class="text-reset">{{ $asset->title }}</a></div>
            <div><a href="{{ route('asset', $asset->ticker_symbol) }}" class="text-muted">{{ $asset->ticker_symbol }}</a></div>
        </div>

    </td>
    <td>@money_format($asset->price * $asset->supply)</td>
    <td data-sort="{{ $asset->price }}">
        @if($asset->ticker_symbol !== 'PEG')
            <a href="{{ route('asset-price-trend', $asset) }}" class="text-decoration-none">
                @money_format($asset->price)
            </a>
            <div class="text-muted">@change_format($asset->price_change) (24h)</div>
        @else
            <div class="text-muted">
                ---
                <a tabindex="0" class="btn btn-link" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ __('pegnet.peg_popover_explain') }}">
                    <i class="now-ui-icons travel_info"></i>
                </a>
            </div>
        @endif
    </td>
    <td data-sort="{{ $asset->exchange_price }}">
        @if($asset->exchange_price > 0.00)
            <a href="{{ route('asset-exchange-price-trend', $asset) }}" class="text-decoration-none">
                @money_format($asset->exchange_price)
            </a>
            <div class="text-muted">@change_format($asset->exchange_price_change) (24h)</div>
        @else
            <div class="text-muted">---</div>
        @endif
    </td>
    <td data-sort="{{ ($asset->volume_price + ($asset->exchange_price * $asset->exchange_volume)) }}">
        <a href="{{ route('asset-volume', $asset) }}" class="text-decoration-none">
            @money_format($asset->volume_price + ($asset->exchange_price * $asset->exchange_volume))
            <div class="text-muted">@number_format($asset->volume + $asset->exchange_volume) {{ $asset->ticker_symbol }}</div>
        </a>
    </td>
    <td data-sort="{{ $asset->supply }}">
        <a href="{{ route('asset-supply', $asset) }}" class="text-decoration-none">
            @number_format($asset->supply) {{ $asset->ticker_symbol }}
        </a>
        <div class="text-muted">@change_format($asset->supply_change) (24h)</div>
    </td>
    <td data-sparkline='@json($asset->getPriceGraphData())' onclick="window.location='{{ route('asset', $asset->ticker_symbol) }}'" style="cursor: pointer">&nbsp;</td>
</tr>
