<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttestationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attestation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_teacher')->nullable();
            $table->integer('id_student')->nullable();
            $table->integer('id_group')->nullable();
            $table->integer('id_rs')->nullable();
            $table->float('sum_visited')->nullable();
            $table->float('sum_labs')->nullable();
            $table->float('sum_practicals')->nullable();
            $table->float('sum_tests')->nullable();
            $table->float('sum_reports')->nullable();
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
        Schema::dropIfExists('attestation');
    }
}
