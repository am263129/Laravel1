<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvs', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name', 50);
            $table->string('genre', 15);
            $table->string('image');
            $table->boolean('streaming_status')->default(0);
            $table->string('streaming_pid', 5)->nullable();
            $table->string('streaming_name', 20)->nullable();
            $table->string('streaming_url');
            $table->string('streaming_resolutions', 7)->nullable();
            $table->string('streaming_type', 7);
            $table->boolean('streaming_transcoding')->default(0);
            $table->string('category', 50);
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
        Schema::dropIfExists('tvs');
    }
}
