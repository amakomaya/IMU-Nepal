<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feed', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('author');
            $table->string('token');
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('url_to_image')->nullable();
            $table->dateTime('published_at')->nullable();
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
        Schema::dropIfExists('news_feed');
    }
}
