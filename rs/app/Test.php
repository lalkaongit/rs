<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = "tests";

    protected $fillable = [
        'id_teacher', 'id_student', 'score_one_test', 'id_discipline', 'id_group', 'id_rs','sum_points'
    ];

}
