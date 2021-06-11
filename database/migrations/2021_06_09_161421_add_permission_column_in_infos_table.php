<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionColumnInInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->unsignedSmallInteger('permission_id')->nullable();
        });
        Schema::table('district_infos', function (Blueprint $table) {
          $table->unsignedSmallInteger('permission_id')->nullable();
        });
        Schema::table('municipality_infos', function (Blueprint $table) {
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
      Schema::table('centers', function (Blueprint $table) {
        $table->unsignedSmallInteger('permission_id')->nullable();
      });
      Schema::table('district_infos', function (Blueprint $table) {
        $table->unsignedSmallInteger('permission_id')->nullable();
      });
      Schema::table('municipality_infos', function (Blueprint $table) {
        $table->unsignedSmallInteger('permission_id')->nullable();
      });
    }
}
