<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table = "labs";

    protected $fillable = [
        'id_teacher', 'id_student', 'score_one_lab', 'id_discipline', 'id_group', 'id_rs', 'sum_points'
    ];

}
