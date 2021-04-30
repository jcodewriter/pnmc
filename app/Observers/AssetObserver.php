<?php

namespace App\Observers;

use App\Asset;
use Illuminate\Support\Facades\Cache;

class AssetObserver
{
    /**
     * Handle the asset "created" event.
     *
     * @param  Asset  $asset
     * @return void
     */
    public function created(Asset $asset)
    {
        Cache::forget('assets:index');
        Cache::forget('asset:' . $asset->ticker_symbol);
    }

    /**
     * Handle the asset "updated" event.
     *
     * @param  Asset  $asset
     * @return void
     */
    public function updated(Asset $asset)
    {
        Cache::forget('assets:index');
        Cache::forget('asset:' . $asset->ticker_symbol);
    }

    /**
     * Handle the asset "force deleted" event.
     *
     * @param  Asset  $asset
     * @return void
     */
    public function forceDeleted(Asset $asset)
    {
        Cache::forget('assets:index');
        Cache::forget('asset:' . $asset->ticker_symbol);
        $asset->history()->delete();
    }
}
