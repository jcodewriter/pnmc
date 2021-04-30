<?php

namespace App\Observers;

use App\AssetHistory;

class AssetHistoryObserver
{
    /**
     * Handle the asset history "created" event.
     *
     * @param  AssetHistory  $assetHistory
     * @return void
     */
    public function created(AssetHistory $assetHistory)
    {
        $this->_handle($assetHistory);
    }
    
    /**
     * Handle the asset history "updated" event.
     *
     * @param  AssetHistory  $assetHistory
     * @return void
     */
    public function updated(AssetHistory $assetHistory)
    {
        $this->_handle($assetHistory);
    }
    
    /**
     * @param AssetHistory $assetHistory
     */
    protected function _handle(AssetHistory $assetHistory)
    {
        $asset = $assetHistory->asset;
        if ($asset && $asset->height <= $assetHistory->height)
        {
            // This history entry is newer than what we have
            $asset->price = $assetHistory->price;
            $asset->supply = $assetHistory->supply;
            $asset->height = $assetHistory->height;
            $asset->updated_at = $assetHistory->dateline;
            $asset->calculateChanges();
            $asset->save();
        }
    }
}
