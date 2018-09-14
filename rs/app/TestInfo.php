<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestInfo extends Model
{
  protected $table = "test_info";

  protected $fillable = [
      'id_rs', 'count_tests', 'score_one', 'test_0', 'test_info'
  ];
}
