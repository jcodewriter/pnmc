<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->primary();
            $table->boolean('is_super_admin')->default(0);
            $table->boolean('can_manage_translations')->default(0);
            $table->boolean('can_manage_users')->default(0);
            $table->boolean('can_manage_assets')->default(0);
            $table->boolean('can_manage_settings')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
