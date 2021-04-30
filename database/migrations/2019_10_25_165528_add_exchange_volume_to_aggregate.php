<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExchangeVolumeToAggregate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volume_aggregate', function (Blueprint $table) {
            $table->unsignedDecimal('exchange_volume', 20, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('volume_aggregate', function (Blueprint $table) {
            $table->dropColumn(['exchange_volume']);
        });
    }
}
