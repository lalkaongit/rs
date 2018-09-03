<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Practical extends Model
{
    protected $table = "practicals";

    protected $fillable = [
        'id_teacher', 'id_student', 'score_one_practical', 'id_discipline', 'id_group', 'id_rs','sum_points'
    ];


}
