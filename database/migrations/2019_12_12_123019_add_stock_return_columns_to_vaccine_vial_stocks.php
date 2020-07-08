<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStockReturnColumnsToVaccineVialStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccine_vial_stocks', function (Blueprint $table) {
            $table->string('return_date_np')->nullable();
            $table->string('return_date_en')->nullable();
            $table->string('return_dose')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccine_vial_stocks', function (Blueprint $table) {
            $table->dropColumn('return_date_np')->nullable();
            $table->dropColumn('return_date_en')->nullable();
            $table->dropColumn('return_dose')->default(0);
        });
    }
}
