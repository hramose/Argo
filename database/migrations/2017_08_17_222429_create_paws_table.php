<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paws', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('race');
            $table->enum('gender',['male','famale']);
			$table->integer('paw_nature_id')->unsigned();
            $table->date('year_of_brith');
            $table->boolean('complete_vaccines');
            $table->date('date_last_vaccine');
            $table->string('last_vaccine');
            $table->boolean('pregnant');
            $table->boolean('under_medication');
            $table->string('medication');
            $table->boolean('neutered_or_sterilized');
            $table->boolean('special_health_condition');
			$table->string('medical_condition');
            $table->string('description');
            $table->string('token');
            $table->timestamps();
            $table->foreign('paw_nature_id')->references('id')->on('paw_natures')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paws');
    }
}
