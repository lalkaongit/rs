<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $table = "disciplines";

    protected $fillable = [
        'name', 'number', 'mdk'
    ];

}
