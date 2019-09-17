<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name',100);
            $table->string('language',30);
            $table->char('movie_id',36)->nullable();
            $table->char('episode_id',36)->nullable();

            $table->timestamps();

            $table->foreign('movie_id')->references('m_id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('episode_id')->references('id')->on('episodes')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtitles');
    }
}
