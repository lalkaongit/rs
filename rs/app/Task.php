<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = "tasks";

    protected $fillable = [
        'name_task', 'id_student', 'id_rs', 'score_one', 'task_0', 'task_1', 'task_2'
    ];

}
