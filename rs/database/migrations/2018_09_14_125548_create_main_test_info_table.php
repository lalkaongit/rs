<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainTestInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_test_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_rs');
            $table->string('test_info')->nullable();
            $table->integer('count_tests')->nullable();
            $table->integer('score_one')->nullable();
            for ($x=0; $x<10; $x++)
            {
             $table->float("test_$x")->nullable();
            }
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
        Schema::dropIfExists('main_test_info');
    }
}
