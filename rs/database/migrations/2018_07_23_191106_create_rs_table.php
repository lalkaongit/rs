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
