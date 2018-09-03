<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = "reports";

    protected $fillable = [
        'id_teacher', 'id_student', 'score_one_report', 'id_discipline', 'id_group', 'id_rs','sum_points'
    ];


}
