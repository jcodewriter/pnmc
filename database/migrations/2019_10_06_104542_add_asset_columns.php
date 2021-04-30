<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table)
        {
            $table->unsignedDecimal('volume_in', 20, 8)
                ->default(0.00000000)
                ->after('volume')
            ;
            $table->unsignedDecimal('volume_out', 20, 8)
                ->default(0.00000000)
                ->after('volume_in')
            ;
            $table->unsignedDecimal('volume_tx', 20, 8)
                ->default(0.00000000)
                ->after('volume_in')
            ;
        });
        
        Schema::table('asset_history', function (Blueprint $table)
        {
            $table->unsignedDecimal('volume_in', 20, 8)
                ->default(0.00000000)
                ->after('volume')
            ;
            $table->unsignedDecimal('volume_out', 20, 8)
                ->default(0.00000000)
                ->after('volume_in')
            ;
            $table->unsignedDecimal('volume_tx', 20, 8)
                ->default(0.00000000)
                ->after('volume_in')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->dropColumn(['volume_in', 'volume_out', 'volume_tx']);
        });
        
        Schema::table('asset_history', function (Blueprint $table) {
            $table->dropColumn(['volume_in', 'volume_out', 'volume_tx']);
        });
    }
}
