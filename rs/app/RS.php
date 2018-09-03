<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RS extends Model
{
    protected $table = "rs";

    protected $fillable = [
        'id_teacher', 'id_institution', 'id_discipline', 'id_group', 'all_points', 'all_points_visits',
        'number_lectures', 'names_tasks', 'count_tasks', 'score_tasks'
    ];

}
