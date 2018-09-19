<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dates extends Model
{
  protected $table = "dates";

  protected $fillable = [
      'id_rs','count_lec','date_0', 'date_1', 'date_2'
  ];
}
