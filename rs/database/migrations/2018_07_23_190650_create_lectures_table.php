<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_teacher')->nullable();
            $table->integer('id_student')->nullable();
            $table->float('id_discipline')->nullable();
            $table->integer('id_group')->nullable();
            $table->integer('id_rs')->nullable();
            $table->float('sum_points')->nullable();
            $table->float('score_one_lecture')->nullable();        
            $table->float('sum_visited')->nullable();
            for ($x=0; $x<90; $x++)
            {
             $table->float("date_$x")->nullable();
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
        Schema::dropIfExists('lectures');
    }
}
