<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('tasks', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('id_student')->nullable();
             $table->integer('id_rs')->nullable();
             $table->string('name_task')->nullable();
             $table->float('score_one')->nullable();
             for ($x=0; $x<20; $x++)
             {
              $table->float("task_$x")->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
