<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    protected $table = "attestation";

    protected $fillable = [
        'id_teacher', 'id_student', 'id_group', 'id_rs', 'sum_visited', 'sum_labs', 'sum_practicals', 'sum_tests', 'sum_reports'
    ];

}
