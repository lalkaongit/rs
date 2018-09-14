<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainTest extends Model
{
  protected $table = "main_test";

  protected $fillable = [
      'id_student', 'id_rs', 'test_0', 'id_main_test'
  ];
}
