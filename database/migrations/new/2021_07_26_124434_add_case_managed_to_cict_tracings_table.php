<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCaseManagedToCictTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cict_tracings', function (Blueprint $table) {
            $table->string('case_managed_at_hospital', '3')->nullable()->after('case_managed_at_other');
            $table->string('case_managed_at_hospital_date', '10')->nullable()->after('case_managed_at_hospital');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cict_tracings', function (Blueprint $table) {
            //
        });
    }
}
