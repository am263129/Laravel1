<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id',20);
            $table->string('from',50);
            $table->string('reply',20);
            $table->boolean('readit')->default(1);
            $table->timestamps();
            $table->foreign('request_id')->references('request_id')->on('supports')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_responses');
    }
}
