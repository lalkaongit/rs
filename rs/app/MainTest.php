<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainTest extends Model
{
  protected $table = "main_test";

  protected $fillable = [
      'id_student', 'id_rs', 'count_questions', 'correct_answer', 'score', 'count_main_tests'
  ];
}
