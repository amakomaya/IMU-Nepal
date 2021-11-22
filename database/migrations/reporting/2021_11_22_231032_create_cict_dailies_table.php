<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_dailies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->tinyInteger('province_id', 1);
            $table->smallInteger('district_id', 3);
            $table->smallInteger('municipality_id', 6);
            $table->integer('total_positive', 4)->default(0);
            $table->integer('total_test', 4)->default(0);
            $table->smallInteger('cict_a_form')->default(0);
            $table->smallInteger('cict_a_contact_count')->default(0);
            $table->smallInteger('cict_b_one_form')->default(0);
            $table->smallInteger('cict_b_two_form')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cict_dailies');
    }
}
