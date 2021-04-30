<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset', function (Blueprint $table) {
            $table->string('ticker_symbol', 10)->primary();
            $table->string('title', 150);
            $table->mediumText('icon_file')->nullable();
            $table->unsignedDecimal('price', 20, 8)->default(0.00000000);
            $table->decimal('price_change', 10, 2)->default(0.00);
            $table->unsignedDecimal('volume', 20, 8)->default(0.00000000);
            $table->unsignedDecimal('supply', 20, 8)->default(0.00000000);
            $table->decimal('supply_change', 10, 2)->default(0.00);
            $table->unsignedInteger('height');
            $table->unsignedInteger('updated_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset');
    }
}
