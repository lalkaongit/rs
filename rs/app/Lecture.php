<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $table = "lectures";

    protected $fillable = [
        'id_teacher', 'id_student', 'score_one_lecture', 'id_group', 'id_discipline', 'id_rs','sum_points', 'sum_visited','date_0', 'date_1', 'date_2'
    ];

}
