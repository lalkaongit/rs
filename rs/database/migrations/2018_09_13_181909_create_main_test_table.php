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
            $table->integer('id_main_test')->nullable();
            $table->integer('id_rs');
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
        Schema::dropIfExists('main_test');
    }
}
