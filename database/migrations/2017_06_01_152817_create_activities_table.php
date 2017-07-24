<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 48);
            $table->integer('subject_id')->unsigned()->index();
            $table->string('subject_type', 48);
            $table->integer('user_id')->unsigned()->index();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->index();
            $table->string('country')->index();
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
        Schema::dropIfExists('activities');
    }
}
