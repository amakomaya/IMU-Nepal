<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrcNNepaliDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('women', function (Blueprint $table) {
            $table->string('orc_id')->nullable()->after('hp_code');
            $table->string('lmp_date_np')->nullable()->after('lmp_date_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('women', function (Blueprint $table) {
            $table->dropColumn('orc_id');
            $table->dropColumn('lmp_date_np');
        });
    }
}
