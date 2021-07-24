<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictCloseContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_close_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cict_id')->unsigned();
            $table->foreign('cict_id')->references('id')->on('cict_tracings')->onDelete('cascade');
            $table->string('case_id', 12);
            $table->string('parent_case_id', 12);
            $table->string('hp_code', 16)->nullable();
            $table->string('checked_by', 50)->nullable();
            $table->string('name', 64)->nullable();
            $table->tinyInteger('age')->nullable();
            $table->enum('age_unit', ['0', '1', '2', '3'])->nullable();
            $table->enum('sex', ['1', '2', '3'])->nullable();
            $table->string('phone', 10)->nullable();
            $table->string('relationship', 3)->nullable();
            $table->string('relationship_others', 40)->nullable();
            $table->string('contact_type', 3)->nullable();
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
        Schema::dropIfExists('cict_close_contacts');
    }
}
