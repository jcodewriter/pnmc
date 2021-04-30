<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExchangePriceHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_price_history', function (Blueprint $table) {
            $table->string('ticker_symbol', 10);
            $table->unsignedInteger('dateline');
            $table->unsignedDecimal('price', 20, 8)->default(0.00000000);
            $table->timestamp('updated_at')->nullable();
            $table->primary(['ticker_symbol', 'dateline'], 'ticker_symbol_date');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_price_history');
    }
}
