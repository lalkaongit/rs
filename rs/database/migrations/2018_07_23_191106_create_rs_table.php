<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_institution')->nullable();
            $table->integer('id_teacher');
            $table->integer('id_discipline');
            $table->integer('id_group');
            $table->float('all_points');
            $table->float('all_points_visits');
            $table->float('number_lectures');
            $table->string('names_tasks')->nullable();
            $table->string('count_tasks')->nullable();
            $table->string('score_tasks')->nullable();
            $table->integer('count_tests')->nullable();
            $table->integer('score_main_test')->nullable();
            $table->integer('score_tests')->nullable();
            $table->integer('count_main_tests')->nullable();
            $table->integer('at_visit')->nullable();
            $table->integer('at_tests')->nullable();
            $table->integer('at_main_tests')->nullable();
            $table->integer('at_bonuses')->nullable();
            $table->string('at_tasks')->nullable();
            $table->string('bonus')->nullable();
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
        Schema::dropIfExists('rs');
    }
}
