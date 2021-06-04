<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProvincePermissionColumnInProvinceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('province_infos', function (Blueprint $table) {
            $table->unsignedSmallInteger('permission_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('province_infos', function (Blueprint $table) {
            $table->unsignedSmallInteger('permission_id')->nullable();
        });
    }
}
