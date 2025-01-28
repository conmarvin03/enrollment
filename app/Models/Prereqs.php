<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prereqs extends Model
{
    protected $fillable = [
        'courseCode','preReq'     ];
}
