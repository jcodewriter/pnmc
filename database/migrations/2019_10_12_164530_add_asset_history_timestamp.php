<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetHistoryTimestamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_history', function (Blueprint $table)
        {
            $table->timestamp('updated_at')->nullable();
        });
        
        DB::update('UPDATE asset_history SET updated_at = FROM_UNIXTIME(dateline)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_history', function (Blueprint $table) {
            $table->dropColumn(['updated_at']);
        });
    }
}
