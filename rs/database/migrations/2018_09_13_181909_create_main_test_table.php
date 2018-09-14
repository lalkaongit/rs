<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_test', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_student');
            $table->integer('id_rs');
            $table->integer('count_questions')->nullable();
            $table->integer('correct_answer')->nullable();
            $table->integer('count_main_tests')->nullable();
            $table->integer('score')->nullable();
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
        Schema::dropIfExists('main_test');
    }
}
