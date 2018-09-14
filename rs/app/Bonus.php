<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
  protected $table = "bonus";

  protected $fillable = [
      'id_student', 'id_rs', 'date', 'count_bonus', 'info'
  ];
}
