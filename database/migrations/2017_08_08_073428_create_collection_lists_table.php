<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatecollectionListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->char('movie_id',36)->nullable();
            $table->char('series_id',36)->nullable();
            $table->integer('collection_id')->unsigned();
            $table->char('uid', 36);
            $table->timestamps();

            $table->foreign('movie_id')->references('m_id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('series_id')->references('t_id')->on('series')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_lists');
    }
}
