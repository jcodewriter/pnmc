<?php

use Illuminate\Database\Seeder;

class RestoreHiddenAssets extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Asset::onlyTrashed()
            ->whereIn('ticker_symbol', ['pTWD', 'pARS'])
            ->restore()
        ;
    }
}
