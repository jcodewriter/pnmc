<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeVolumeHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_volume_history', function (Blueprint $table) {
            $table->string('ticker_symbol', 10);
            $table->string('quote_symbol', 10);
            $table->string('exchange', 25);
            $table->unsignedInteger('dateline');
            $table->unsignedDecimal('volume', 20, 8)->default(0.00000000);
            $table->timestamp('updated_at')->nullable();
            $table->primary(['ticker_symbol', 'quote_symbol', 'exchange', 'dateline'], 'ticker_symbol_exchange_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_volume_history');
    }
}
