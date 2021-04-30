<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAssetHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App\AssetHistory::where('ticker_symbol', 'pPEG')
            ->update(['ticker_symbol' => 'PEG'])
        ;
    
        App\AssetHistory::where('ticker_symbol', 'pSilver')
            ->update(['ticker_symbol' => 'pSILVER'])
        ;
    
        App\Asset::where('ticker_symbol', 'pSilver')
            ->update(['ticker_symbol' => 'pSILVER'])
        ;
    
        $updated = App\Asset::where('ticker_symbol', 'PEG')
            ->update(['icon_file' => 'peg.png'])
        ;
        if ($updated)
        {
            App\Asset::where('ticker_symbol', 'pPEG')
                ->forceDelete()
            ;
        }
        else
        {
            App\Asset::where('ticker_symbol', 'pPEG')
                ->update(['ticker_symbol' => 'PEG', 'icon_file' => 'peg.png'])
            ;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\AssetHistory::where('ticker_symbol', 'PEG')
            ->update(['ticker_symbol' => 'pPEG'])
        ;
    
        App\AssetHistory::where('ticker_symbol', 'pSILVER')
            ->update(['ticker_symbol' => 'pSilver'])
        ;
    
        App\Asset::where('ticker_symbol', 'pSILVER')
            ->update(['ticker_symbol' => 'pSilver'])
        ;
    
        App\Asset::where('ticker_symbol', 'PEG')
            ->update(['ticker_symbol' => 'pPEG', 'icon_file' => 'ppeg.png'])
        ;
    }
}
