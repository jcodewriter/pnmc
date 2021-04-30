<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_history', function (Blueprint $table) {
            $table->string('ticker_symbol', 10);
            $table->unsignedInteger('height');
            $table->unsignedDecimal('price', 20, 8)->default(0.00000000);
            $table->unsignedDecimal('volume', 20, 8)->default(0.00000000);
            $table->unsignedDecimal('supply', 20, 8)->default(0.00000000);
            $table->unsignedInteger('dateline');
            $table->primary(['ticker_symbol', 'height'], 'ticker_symbol_height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_history');
    }
}
