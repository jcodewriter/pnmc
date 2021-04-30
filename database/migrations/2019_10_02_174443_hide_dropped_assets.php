<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideDroppedAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App\Asset::whereIn('ticker_symbol', ['pXPT', 'pXPD', 'pTWD', 'pARS'])
            ->delete()
        ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\Asset::onlyTrashed()
            ->whereIn('ticker_symbol', ['pXPT', 'pXPD', 'pTWD', 'pARS'])
            ->restore()
        ;
    }
}
