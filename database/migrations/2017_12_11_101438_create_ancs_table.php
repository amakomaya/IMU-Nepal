<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ancs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('woman_token');
            $table->date('visit_date');
            $table->string('weight')->nullable();
            $table->string('anemia')->nullable();
            $table->string('swelling')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('uterus_height')->nullable();
            $table->string('baby_presentation')->nullable();
            $table->string('baby_heart_beat');
            $table->string('other')->nullable();
            $table->string('iron_pills')->nullable();
            $table->string('worm_medicine')->nullable();
            $table->string('td_reg_no')->nullable();
            $table->string('td_vaccine')->nullable();
            $table->string('situation')->nullable();
            $table->string('checked_by')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->char('hp_code', 16);
            $table->boolean('status');
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
        Schema::dropIfExists('ancs');
    }
}
