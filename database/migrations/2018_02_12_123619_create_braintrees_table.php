<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBraintreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('braintrees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_id',40);
            $table->string('plan_name',40);
            $table->string('plan_amount',15);
            $table->string('plan_interval',15)->nullable();
            $table->string('plan_currency', 15);
            $table->string('plan_trial', 10)->nullable();

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
        Schema::dropIfExists('braintrees');
    }
}
