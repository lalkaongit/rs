<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = "tests";

    protected $fillable = [
        'id_student', 'id_rs', 'count_questions', 'correct_answer', 'count_tests', 'score_one'
    ];

}
