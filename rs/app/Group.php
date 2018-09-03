<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Group extends Authenticatable
{
    use Notifiable;
    protected $table = "groups";

    protected $fillable = [
        'year_adms', 'id_specialty'
    ];

}
