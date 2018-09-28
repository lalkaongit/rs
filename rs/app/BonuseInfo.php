<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonuseInfo extends Model
{
  protected $table = "bonuse_info";

  protected $fillable = [
      'id_teacher', 'values', 'themes'
  ];
}
