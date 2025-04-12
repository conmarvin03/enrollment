<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    protected $fillable = [
        'pID',
        'kldID',
        'password',
        'fName',
        'lName',
        'mName',
        'gender',
        'bday',
        'email',
        'address',
        'img','ay','section'
    ];
}
