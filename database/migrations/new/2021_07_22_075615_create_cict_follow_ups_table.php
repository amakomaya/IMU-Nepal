<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_follow_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('case_id', 50);
            $table->string('token', 50);
            $table->string('parent_case_id', 50);
            $table->string('woman_token', 50)->nullable();
            $table->string('hp_code', 16)->nullable();
            $table->string('checked_by', 50)->nullable();
            $table->string('regdev', 10)->nullable();
            
            $table->string('completion_date', 10)->nullable();
            $table->string('high_exposure', 3)->nullable();
            
            $table->string('date_of_follow_up_0', 10)->nullable();
            $table->tinyInteger('no_symptoms_0')->nullable();
            $table->tinyInteger('fever_0')->nullable();
            $table->tinyInteger('runny_nose_0')->nullable();
            $table->tinyInteger('cough_0')->nullable();
            $table->tinyInteger('sore_throat_0')->nullable();
            $table->tinyInteger('breath_0')->nullable();
            $table->string('symptoms_other_0', 64)->nullable();
            
            $table->string('date_of_follow_up_1', 10)->nullable();
            $table->tinyInteger('no_symptoms_1')->nullable();
            $table->tinyInteger('fever_1')->nullable();
            $table->tinyInteger('runny_nose_1')->nullable();
            $table->tinyInteger('cough_1')->nullable();
            $table->tinyInteger('sore_throat_1')->nullable();
            $table->tinyInteger('breath_1')->nullable();
            $table->string('symptoms_other_1', 64)->nullable();
            
            $table->string('date_of_follow_up_2', 10)->nullable();
            $table->tinyInteger('no_symptoms_2')->nullable();
            $table->tinyInteger('fever_2')->nullable();
            $table->tinyInteger('runny_nose_2')->nullable();
            $table->tinyInteger('cough_2')->nullable();
            $table->tinyInteger('sore_throat_2')->nullable();
            $table->tinyInteger('breath_2')->nullable();
            $table->string('symptoms_other_2', 64)->nullable();
            
            $table->string('date_of_follow_up_3', 10)->nullable();
            $table->tinyInteger('no_symptoms_3')->nullable();
            $table->tinyInteger('fever_3')->nullable();
            $table->tinyInteger('runny_nose_3')->nullable();
            $table->tinyInteger('cough_3')->nullable();
            $table->tinyInteger('sore_throat_3')->nullable();
            $table->tinyInteger('breath_3')->nullable();
            $table->string('symptoms_other_3', 64)->nullable();
            
            $table->string('date_of_follow_up_4', 10)->nullable();
            $table->tinyInteger('no_symptoms_4')->nullable();
            $table->tinyInteger('fever_4')->nullable();
            $table->tinyInteger('runny_nose_4')->nullable();
            $table->tinyInteger('cough_4')->nullable();
            $table->tinyInteger('sore_throat_4')->nullable();
            $table->tinyInteger('breath_4')->nullable();
            $table->string('symptoms_other_4', 64)->nullable();
            
            $table->string('date_of_follow_up_5', 10)->nullable();
            $table->tinyInteger('no_symptoms_5')->nullable();
            $table->tinyInteger('fever_5')->nullable();
            $table->tinyInteger('runny_nose_5')->nullable();
            $table->tinyInteger('cough_5')->nullable();
            $table->tinyInteger('sore_throat_5')->nullable();
            $table->tinyInteger('breath_5')->nullable();
            $table->string('symptoms_other_5', 64)->nullable();
            
            $table->string('date_of_follow_up_6', 10)->nullable();
            $table->tinyInteger('no_symptoms_6')->nullable();
            $table->tinyInteger('fever_6')->nullable();
            $table->tinyInteger('runny_nose_6')->nullable();
            $table->tinyInteger('cough_6')->nullable();
            $table->tinyInteger('sore_throat_6')->nullable();
            $table->tinyInteger('breath_6')->nullable();
            $table->string('symptoms_other_6', 64)->nullable();
            
            $table->string('date_of_follow_up_7', 10)->nullable();
            $table->tinyInteger('no_symptoms_7')->nullable();
            $table->tinyInteger('fever_7')->nullable();
            $table->tinyInteger('runny_nose_7')->nullable();
            $table->tinyInteger('cough_7')->nullable();
            $table->tinyInteger('sore_throat_7')->nullable();
            $table->tinyInteger('breath_7')->nullable();
            $table->string('symptoms_other_7', 64)->nullable();
            
            $table->string('date_of_follow_up_8', 10)->nullable();
            $table->tinyInteger('no_symptoms_8')->nullable();
            $table->tinyInteger('fever_8')->nullable();
            $table->tinyInteger('runny_nose_8')->nullable();
            $table->tinyInteger('cough_8')->nullable();
            $table->tinyInteger('sore_throat_8')->nullable();
            $table->tinyInteger('breath_8')->nullable();
            $table->string('symptoms_other_8', 64)->nullable();
            
            $table->string('date_of_follow_up_9', 10)->nullable();
            $table->tinyInteger('no_symptoms_9')->nullable();
            $table->tinyInteger('fever_9')->nullable();
            $table->tinyInteger('runny_nose_9')->nullable();
            $table->tinyInteger('cough_9')->nullable();
            $table->tinyInteger('sore_throat_9')->nullable();
            $table->tinyInteger('breath_9')->nullable();
            $table->string('symptoms_other_9', 64)->nullable();
            
            $table->string('date_of_follow_up_10', 10)->nullable();
            $table->tinyInteger('no_symptoms_10')->nullable();
            $table->tinyInteger('fever_10')->nullable();
            $table->tinyInteger('runny_nose_10')->nullable();
            $table->tinyInteger('cough_10')->nullable();
            $table->tinyInteger('sore_throat_10')->nullable();
            $table->tinyInteger('breath_10')->nullable();
            $table->string('symptoms_other_10', 64)->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('cict_follow_ups');
    }
}
