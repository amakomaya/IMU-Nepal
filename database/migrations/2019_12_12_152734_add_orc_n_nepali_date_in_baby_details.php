<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrcNNepaliDateInBabyDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baby_details', function (Blueprint $table) {
            $table->string('orc_id')->nullable()->after('hp_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baby_details', function (Blueprint $table) {
            $table->dropColumn('orc_id');
        });
    }
}
