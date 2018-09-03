<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{

    protected $table = "educational_institution";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
