<?php

Breadcrumbs::for('home', function ($trail)
{
    $trail->push(trans('generic.home'), route('home'));
});

Breadcrumbs::for('market_cap', function ($trail)
{
    $trail->push(trans('generic.home'), route('home'));
    $trail->push(trans('pegnet.total_market_cap'), route('market-cap'));
});

Breadcrumbs::for('asset', function ($trail, $asset)
{
    $trail->push(trans('generic.home'), route('home'));
    if (!is_object($asset))
    {
//        $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
    }
    else
    {
//        $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
        $trail->push($asset->title, route('asset', $asset->ticker_symbol));
    }
});

Breadcrumbs::for('asset_price', function ($trail, $asset)
{
    $trail->push(trans('generic.home'), route('home'));
//    $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
    $trail->push($asset->title, route('asset', $asset->ticker_symbol));
    $trail->push(trans('pegnet.price_trend'), route('asset-price-trend', $asset->ticker_symbol));
});

Breadcrumbs::for('asset_exchange_price', function ($trail, $asset)
{
    $trail->push(trans('generic.home'), route('home'));
//    $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
    $trail->push($asset->title, route('asset', $asset->ticker_symbol));
    $trail->push(trans('pegnet.exchange_price_trend'), route('asset-exchange-price-trend', $asset->ticker_symbol));
});

Breadcrumbs::for('asset_volume', function ($trail, $asset)
{
    $trail->push(trans('generic.home'), route('home'));
//    $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
    $trail->push($asset->title, route('asset', $asset->ticker_symbol));
    $trail->push(trans('pegnet.volume_trend'), route('asset-volume', $asset->ticker_symbol));
});

Breadcrumbs::for('asset_supply', function ($trail, $asset)
{
    $trail->push(trans('generic.home'), route('home'));
//    $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
    $trail->push($asset->title, route('asset', $asset->ticker_symbol));
    $trail->push(trans('pegnet.supply_trend'), route('asset-supply', $asset->ticker_symbol));
});

Breadcrumbs::for('asset_rich_list', function ($trail, $asset)
{
    $trail->push(trans('generic.home'), route('home'));
//    $trail->push(trans('pegnet.all_assets'), route('asset', 'all'));
    $trail->push($asset->title, route('asset', $asset->ticker_symbol));
    $trail->push(trans('pegnet.richest_addresses'), route('asset-rich-list', $asset->ticker_symbol));
});

Breadcrumbs::for('errors.404', function ($trail)
{
    $trail->parent('home');
    $trail->push('Page Not Found');
});
